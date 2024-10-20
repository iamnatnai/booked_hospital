<?php
include 'config.php';

if (isset($_GET['id'])) {
    $clinic_id = $_GET['id'];

    // Prepare and execute the query
    $query = "SELECT id, name, th_clinicname FROM clinics WHERE id = ?";
    
    // เปลี่ยนจาก $conn เป็น $pdo
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1, $clinic_id, PDO::PARAM_INT); // ใช้ bindParam แทน bind_param

    if ($stmt->execute()) {
        $clinic = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($clinic) {
            // ส่งคืนข้อมูลคลินิกในรูปแบบ JSON
            echo json_encode($clinic);
        } else {
            // ถ้าไม่พบคลินิก
            echo json_encode(['error' => 'ไม่พบคลินิกที่ต้องการ']);
        }
    } else {
        // ถ้ามีข้อผิดพลาดในการ execute
        echo json_encode(['error' => 'เกิดข้อผิดพลาดในการดึงข้อมูล']);
    }
} else {
    // ถ้าไม่พบค่าของ id ใน GET
    echo json_encode(['error' => 'ไม่พบค่าของ ID']);
}
