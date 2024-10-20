<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์มและลบเครื่องหมาย -
    $national_id = str_replace('-', '', $_POST['national_id']);
    $birth_date = $_POST['formatted_birth_date'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = str_replace('-', '', $_POST['phone_number']);
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
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $national_id, $birth_date, $first_name, $last_name, $phone_number, $blood_thinner, $medical_conditions);

    if ($stmt->execute()) {
        $_SESSION['success'] = "สมัครผู้ป่วยใหม่สำเร็จ!";

        // ส่งการแจ้งเตือนผ่าน Line Notify
        $lineNotifyToken = 'e3QW71WfPJH8nQGXLrQcL31izXFPwM5Jnh5qFRAdUmZ'; // ใส่โทเค็นของคุณที่นี่
        $message = "มีการสมัครผู้ป่วยใหม่:\n".
                   "เลขบัตรประชาชน: $national_id\n".
                   "วัน/เดือน/ปี เกิด: $birth_date\n".
                   "ชื่อ: $first_name\n".
                   "นามสกุล: $last_name\n".
                   "เบอร์โทรศัพท์: $phone_number\n".
                   "ใช้ยาละลายลิ่มเลือด: $blood_thinner\n".
                   "โรคประจำตัว: $medical_conditions";
        
        $data = [
            'message' => $message
        ];
        
        $ch = curl_init('https://notify-api.line.me/api/notify');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $lineNotifyToken));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $conn->error;
        header("Location: form_page.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
