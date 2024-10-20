<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $line_id = $_POST['line_id'];
    $message_text = $_POST['message'];

    // ตรวจสอบความถูกต้องของข้อมูล
    if (empty($line_id) || empty($message_text)) {
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
        exit();
    }

    // Channel Access Token จาก LINE OA
    $lineChannelToken = 'YCbvyZyos8iq5RD6WuF55VtwKLwM2wYGCbN7wOWykPebfErUCVP9WMVE0+LZm9GHCNuBKijXNUsR01XrtR4wrrc5SNwohh7FqimN18jNZvcm0yJh429Jtafqj3tvRMJvH7iFBnQeDmHYeYbQKpCGDQdB04t89/1O/w1cDnyilFU='; // ใส่ Channel Access Token ของคุณที่นี่

    // ข้อความที่ต้องการส่ง
    $message = [
        'to' => $line_id,  // User ID หรือ Group ID ของผู้รับ
        'messages' => [[
            'type' => 'text',
            'text' => $message_text
        ]]
    ];

    // API URL
    $url = 'https://api.line.me/v2/bot/message/push';

    // ตั้งค่า cURL สำหรับส่ง request
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $lineChannelToken
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // ได้สถานะ HTTP Code
    curl_close($ch);

    // ตรวจสอบสถานะการตอบกลับจาก LINE API
    if ($httpCode == 200) {
        echo "ส่งข้อความสำเร็จ: $message_text";
    } else {
        echo "เกิดข้อผิดพลาด HTTP Status: " . $httpCode;
        echo "<br>รายละเอียดการตอบกลับจาก LINE: " . $result;
    }
}
?>
