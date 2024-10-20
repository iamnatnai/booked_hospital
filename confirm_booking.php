<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// เริ่มต้นการทำงานของเซสชัน
session_start();

// ตรวจสอบว่าเซสชันของผู้ใช้มีค่าหรือไม่
if (!isset($_SESSION['citizen'])) {
    header("Location: login.html");
    exit();
}

// รับข้อมูลจากการร้องขอ (POST)
$clinic_id = $_POST['clinic_for_book'];
$date_booked = $_POST['date_booked'];
$time_slot_id = $_POST['time_slot_id'];
$patient_name = $_POST['name_patient'];
$national_id = $_POST['national_id'];
$phone_number = $_POST['phone_number'];

// เชื่อมต่อกับฐานข้อมูล
include 'configz.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบข้อมูลที่จำเป็นทั้งหมด
if (!$clinic_id || !$date_booked || !$time_slot_id || !$patient_name || !$national_id || !$phone_number) {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลที่ส่งมาผิดพลาด']);
    exit();
}

try {
    // สร้าง booked_id
    // แปลงวันที่เป็นรูปแบบที่ต้องการ
    $formatted_date = date('Ymd', strtotime($date_booked)); // YYYYMMDD

    // ค้นหาหมายเลขล่าสุดในฐานข้อมูลที่ใช้สำหรับ booked_id
    $stmt = $pdo->prepare("SELECT MAX(CAST(SUBSTRING(booked_id, 15) AS UNSIGNED)) AS max_id 
                            FROM booked_confirm 
                            WHERE booked_id LIKE :prefix");
    
    // prefix ที่ต้องการค้นหา (date + clinic_id)
    $prefix = $formatted_date . $clinic_id . '%'; 
    $stmt->bindParam(':prefix', $prefix);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // เพิ่มเลขรันขึ้นเรื่อย ๆ
    $next_id = $result['max_id'] ? intval($result['max_id']) + 1 : 1; // เพิ่ม 1 หรือเริ่มจาก 1 ถ้ายังไม่มี
    
    // ทำให้เลขรันมีความยาว 6 หลัก
    $booked_id_suffix = str_pad($next_id, 6, '0', STR_PAD_LEFT); // 0-fill to 6 digits
    $booked_id = $formatted_date . $clinic_id . $booked_id_suffix; // รวมเข้าด้วยกัน

    // เตรียมคำสั่ง SQL เพื่อบันทึกการจอง
    $stmt = $pdo->prepare("
        INSERT INTO booked_confirm 
        (national_id, name_patient, clinic_id, time_slot_id, date_booked, date_booked_stamp, doctor_name, booked_count, status, confirm_date, booked_id) 
        VALUES 
        (:national_id, :patient_name, :clinic_id, :time_slot_id, :date_booked, CURRENT_TIMESTAMP, NULL, 1, 'waiting', NULL, :booked_id)
    ");

    // ผูกค่ากับคำสั่ง SQL
    $stmt->bindParam(':national_id', $national_id);
    $stmt->bindParam(':patient_name', $patient_name);
    $stmt->bindParam(':clinic_id', $clinic_id);
    $stmt->bindParam(':time_slot_id', $time_slot_id);
    $stmt->bindParam(':date_booked', $date_booked);
    $stmt->bindParam(':booked_id', $booked_id); // ผูก booked_id ที่สร้างขึ้น

    // ดำเนินการบันทึกข้อมูลลงในฐานข้อมูล
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'การจองได้รับการยืนยัน', 'booked_id' => $booked_id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}

// แสดงข้อมูลสำหรับการตรวจสอบ
var_dump($clinic_id, $date_booked, $time_slot_id, $patient_name, $national_id, $phone_number, $booked_id);
exit();
?>
