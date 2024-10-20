<?php
// get_time_slot.php

// นำเข้าไฟล์ config.php ที่มีการเชื่อมต่อฐานข้อมูล PDO
include 'config.php';

// ตรวจสอบว่าได้รับ 'id' ผ่าน GET หรือไม่
if (isset($_GET['id'])) {
    $timeSlotId = $_GET['id'];

    try {
        // เตรียม SQL query เพื่อดึงข้อมูลช่วงเวลา
        $query = "SELECT start_time, end_time FROM time_slots WHERE id = :timeSlotId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':timeSlotId', $timeSlotId, PDO::PARAM_INT);
        
        // Execute the query
        $stmt->execute();
        
        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            echo json_encode($result); // ส่งข้อมูลกลับเป็น JSON
        } else {
            echo json_encode(['error' => 'ไม่พบข้อมูลช่วงเวลาที่ต้องการ']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ไม่มีข้อมูล id ถูกส่งมา']);
}
?>
