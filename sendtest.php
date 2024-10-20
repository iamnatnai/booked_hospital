<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// เริ่มต้นการทำงานของเซสชัน
session_start();

// ตรวจสอบว่าเซสชันของผู้ใช้มีค่าหรือไม่
if (!isset($_SESSION['citizen'])) {
    // ถ้าไม่มีค่าของเซสชัน ให้เปลี่ยนเส้นทางไปยังหน้า login
    header("Location: login.html");
    exit();
}

// รับข้อมูลจากการร้องขอ (POST) หรือกำหนดค่าตัวอย่างถ้าไม่มี
$clinic_id = 2; // เปลี่ยนค่าเป็น clinic_id ที่ต้องการ
$date_booked = '2024-10-28'; // วันที่ที่จอง
$time_slot_id = 3; // ID ของช่วงเวลาที่เลือก
$patient_name = 'ทดสอบชื่อ ทดสอบนามสกุล'; // ชื่อผู้ป่วย
$national_id = '1111111111113'; // หมายเลขบัตรประชาชน
$phone_number = '0972345678'; // หมายเลขโทรศัพท์

// เชื่อมต่อกับฐานข้อมูล
include 'config.php'; // เชื่อมต่อฐานข้อมูล

try {
    // เตรียมคำสั่ง SQL เพื่อบันทึกการจอง
    $stmt = $pdo->prepare("
        INSERT INTO booked_confirm 
        (national_id, name_patient, clinic_id, time_slot_id, date_booked, date_booked_stamp, doctor_name, booked_count, status, confirm_date) 
        VALUES 
        (:national_id, :patient_name, :clinic_id, :time_slot_id, :date_booked, CURRENT_TIMESTAMP, NULL, 1, 'waiting', NULL)
    ");

    // ผูกค่ากับคำสั่ง SQL
    $stmt->bindParam(':national_id', $national_id);
    $stmt->bindParam(':patient_name', $patient_name); 
    $stmt->bindParam(':clinic_id', $clinic_id); 
    $stmt->bindParam(':time_slot_id', $time_slot_id);
    $stmt->bindParam(':date_booked', $date_booked);

    // ดำเนินการบันทึกข้อมูลลงในฐานข้อมูล
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'การจองได้รับการยืนยัน']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>
