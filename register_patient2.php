<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $national_id = $_POST['national_id'];
    $birth_date = $_POST['formatted_birth_date'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $blood_thinner = $_POST['blood_thinner'];
    $medical_conditions = isset($_POST['medical_condition']) ? implode(', ', $_POST['medical_condition']) : '';

    // ตรวจสอบความถูกต้องของข้อมูลเบื้องต้น
    if (empty($national_id) || empty($birth_date) || empty($first_name) || empty($last_name) || empty($phone_number) || empty($blood_thinner)) {
        $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header("Location: form_page.php");
        exit();
    }

    // เชื่อมต่อฐานข้อมูล
    $servername = "localhost";
$username = "kasemra2_book";
$password = "kasemrad@64";
$dbname = "kasemra2_book";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL statement เพื่อบันทึกข้อมูล
    $sql = "INSERT INTO patients (national_id, birth_date, first_name, last_name, phone_number, blood_thinner, medical_conditions)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $national_id, $birth_date, $first_name, $last_name, $phone_number, $blood_thinner, $medical_conditions);

    if ($stmt->execute()) {
        // บันทึกสำเร็จ
        $_SESSION['success'] = "สมัครผู้ป่วยใหม่สำเร็จ!";
        header("Location: index.php");  // Redirect to index.php
        exit();  // Stop further execution after redirection
    } else {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $conn->error;
        header("Location: regis_patient.phpe.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
