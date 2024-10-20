<?php
$channelAccessToken = 'YCbvyZyos8iq5RD6WuF55VtwKLwM2wYGCbN7wOWykPebfErUCVP9WMVE0+LZm9GHCNuBKijXNUsR01XrtR4wrrc5SNwohh7FqimN18jNZvcm0yJh429Jtafqj3tvRMJvH7iFBnQeDmHYeYbQKpCGDQdB04t89/1O/w1cDnyilFU='; // Channel Access Token ของคุณ
$userId = 'USER_ID'; // ใส่ User ID ที่ต้องการดึงโปรไฟล์

$url = 'https://api.line.me/v2/bot/profile/' . $userId;

$headers = [
    'Authorization: Bearer ' . $channelAccessToken
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo $response; // จะแสดงโปรไฟล์ของผู้ใช้ รวมถึง User ID
?>
