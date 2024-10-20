<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// เชื่อมต่อฐานข้อมูล
$servername = "localhost"; // เปลี่ยนเป็นเซิร์ฟเวอร์ของคุณ
$username = "kasemra2_book"; // เปลี่ยนเป็นชื่อผู้ใช้ฐานข้อมูลของคุณ
$password = "kasemrad@64"; // เปลี่ยนเป็นรหัสผ่านฐานข้อมูลของคุณ
$dbname = "kasemra2_book"; // เปลี่ยนเป็นชื่อฐานข้อมูลของคุณ

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if (!isset($_POST['doctor_id']) || empty($_POST['doctor_id'])) {
    die("รหัสแพทย์ไม่ถูกต้อง");
}

if (!isset($_POST['selected_days']) || !is_array($_POST['selected_days'])) {
    die("ข้อมูลวันไม่ถูกต้อง");
}

if (!isset($_POST['selected_times']) || !is_array($_POST['selected_times'])) {
    die("ข้อมูลช่วงเวลาไม่ถูกต้อง");
}

$doctor_id = $_POST['doctor_id'];
$selected_days = $_POST['selected_days']; // วันที่เลือกเป็นอาร์เรย์
$selected_times = $_POST['selected_times']; // เวลาที่เลือกเป็นอาร์เรย์

// ลบข้อมูลเก่าก่อนการบันทึกใหม่
$sql = "DELETE FROM doctor_schedule WHERE doctor_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("ไม่สามารถเตรียมคำสั่ง SQL ได้: " . $conn->error);
}
$stmt->bind_param("i", $doctor_id);
$stmt->execute();

// บันทึกวันและช่วงเวลาใหม่
$sql = "INSERT INTO doctor_schedule (doctor_id, day_of_week, time_slot) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("ไม่สามารถเตรียมคำสั่ง SQL ได้: " . $conn->error);
}

// ลูปเพื่อบันทึกข้อมูล
foreach ($selected_days as $day) {
    foreach ($selected_times as $time) {
        $stmt->bind_param("iss", $doctor_id, $day, $time);
        if (!$stmt->execute()) {
            die("ไม่สามารถบันทึกข้อมูลได้: " . $stmt->error);
        }
    }
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();

// ส่งผลลัพธ์กลับไปยังผู้ใช้
echo "บันทึกข้อมูลสำเร็จ";
?>