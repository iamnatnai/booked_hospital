<?php
// รับข้อมูลจาก Webhook ของ LINE
$input = file_get_contents('php://input');

// แปลงข้อมูลที่ได้รับมาเป็น JSON
$events = json_decode($input, true);

$channelAccessToken = 'YCbvyZyos8iq5RD6WuF55VtwKLwM2wYGCbN7wOWykPebfErUCVP9WMVE0+LZm9GHCNuBKijXNUsR01XrtR4wrrc5SNwohh7FqimN18jNZvcm0yJh429Jtafqj3tvRMJvH7iFBnQeDmHYeYbQKpCGDQdB04t89/1O/w1cDnyilFU='; // ใส่ CHANNEL_ACCESS_TOKEN ของคุณที่นี่

// ตรวจสอบว่ามีข้อมูลส่งมาจาก LINE หรือไม่
if (!is_null($events['events'])) {
    // วนลูปเพื่อดึงข้อมูลใน events
    foreach ($events['events'] as $event) {
        // ตรวจสอบว่าเป็นข้อความที่ส่งมาจากผู้ใช้
        if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
            $userId = $event['source']['userId']; // ดึง userId จาก event

            // เรียกใช้ Get Profile API เพื่อดึงข้อมูลเพิ่มเติมของผู้ใช้
            $profile = getUserProfile($userId, $channelAccessToken);

            // แสดงข้อมูล User ID และชื่อผู้ใช้
            echo "User ID: " . $userId . "<br>";
            echo "Display Name: " . $profile['displayName'] . "<br>";
            echo "Picture URL: " . $profile['pictureUrl'] . "<br>";
        }
    }
}

// ฟังก์ชันสำหรับเรียกใช้ Get Profile API
function getUserProfile($userId, $accessToken) {
    $url = 'https://api.line.me/v2/bot/profile/' . $userId;

    $headers = [
        'Authorization: Bearer ' . $accessToken
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}
?>
