<?php
session_start();

// ตรวจสอบว่าเซสชันของผู้ใช้มีค่าหรือไม่
if (!isset($_SESSION['user'])) {
    // ถ้าไม่มีค่าของเซสชัน ให้เปลี่ยนเส้นทางไปยังหน้า login
    header("Location: login.html");
    exit();
}

// แสดงค่าทั้งหมดในเซสชัน
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>ข้อมูลผู้ใช้</h1>
    <table>
        <tr>
            <th>ชื่อ</th>
            <th>ค่า</th>
        </tr>
        <?php
        // แสดงค่าทั้งหมดในเซสชัน
        foreach ($_SESSION as $key => $value) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($key) . "</td>";
            echo "<td>" . htmlspecialchars(print_r($value, true)) . "</td>"; // ใช้ print_r เพื่อแสดงข้อมูลที่ซับซ้อน
            echo "</tr>";
        }
        ?>
    </table>
    <br>
    <a href="logout.php">ออกจากระบบ</a>
</body>
</html>
