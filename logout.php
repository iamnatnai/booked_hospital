<?php
session_start();

// ลบค่าทั้งหมดในเซสชัน
session_unset();

// ทำลายเซสชัน
session_destroy();

// เปลี่ยนเส้นทางไปยังหน้า login
header("Location: login.html");
exit();
?>
