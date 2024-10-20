<?php
// config.php

// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "kasemra2_book";
$password = "kasemrad@64";
$dbname = "kasemra2_book";

try {
    // สร้างการเชื่อมต่อ PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // เปิดการรายงานข้อผิดพลาด
    // การเชื่อมต่อสำเร็จ
    echo "Connected successfully";
} catch (PDOException $e) {
    // การเชื่อมต่อล้มเหลว
    echo "Connection failed: " . $e->getMessage();
}
?>
