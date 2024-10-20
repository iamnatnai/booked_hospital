<?php
// เริ่มต้นเซสชัน
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'configz.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีค่า national_id ในเซสชันหรือไม่
if (!isset($_SESSION['national_id'])) {
    echo "ไม่พบข้อมูลการจอง";
    exit();
}

$national_id = $_SESSION['national_id'];

try {
    // ดึงข้อมูลการจองจากฐานข้อมูลโดยใช้ national_id
    $stmt = $pdo->prepare("
        SELECT booked_id, name_patient, clinic_id, time_slot_id, date_booked, doctor_name, status 
        FROM booked_confirm 
        WHERE national_id = :national_id
    ");
    $stmt->bindParam(':national_id', $national_id);
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมด

    if (!$bookings) {
        echo "ไม่พบข้อมูลการจองที่ตรงกับหมายเลขบัตรประจำตัวประชาชนนี้";
        exit();
    }

    // เตรียมข้อมูลคลินิกและช่วงเวลา
    $clinics = [];
    $time_slots = [];

    foreach ($bookings as $booking) {
        // ดึงข้อมูลคลินิก
        if (!isset($clinics[$booking['clinic_id']])) {
            $clinic_stmt = $pdo->prepare("
                SELECT name 
                FROM clinics 
                WHERE id = :clinic_id
            ");
            $clinic_stmt->bindParam(':clinic_id', $booking['clinic_id']);
            $clinic_stmt->execute();
            $clinics[$booking['clinic_id']] = $clinic_stmt->fetch(PDO::FETCH_ASSOC);
        }

        // ดึงข้อมูลช่วงเวลา
        if (!isset($time_slots[$booking['time_slot_id']])) {
            $time_slot_stmt = $pdo->prepare("
                SELECT start_time, end_time 
                FROM time_slots 
                WHERE id = :time_slot_id
            ");
            $time_slot_stmt->bindParam(':time_slot_id', $booking['time_slot_id']);
            $time_slot_stmt->execute();
            $time_slots[$booking['time_slot_id']] = $time_slot_stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    exit();
}

// แปลงวันที่เป็นภาษาไทย
function formatThaiDate($date) {
    $months = array(
        "01" => "มกราคม", "02" => "กุมภาพันธ์", "03" => "มีนาคม", "04" => "เมษายน",
        "05" => "พฤษภาคม", "06" => "มิถุนายน", "07" => "กรกฎาคม", "08" => "สิงหาคม",
        "09" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม"
    );

    $year = date("Y", strtotime($date)) + 543;
    $month = $months[date("m", strtotime($date))];
    $day = date("d", strtotime($date));

    return "วัน" . $day . " " . $month . " " . $year;
}

// ฟังก์ชันสำหรับกำหนดสีและข้อความสถานะ
function getStatusMessage($status) {
    switch ($status) {
        case 'accept':
            return ['class' => 'status-accepted', 'text' => 'การจองของคุณได้รับการอนุมัติแล้ว!'];
        case 'waiting':
            return ['class' => 'status-waiting', 'text' => 'การจองของคุณอยู่ในระหว่างรอการอนุมัติ'];
        case 'decline':
            return ['class' => 'status-declined', 'text' => 'การจองของคุณถูกปฏิเสธ'];
        default:
            return ['class' => 'status-unknown', 'text' => 'ไม่ทราบสถานะการจอง'];
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลการจองคิว - โรงพยาบาลเกษมราษฎร์ ประชาชื่น</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'TH SarabunPSK', sans-serif;
            background-color: #f4f4f4;
            color: #000000;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 10px;
            padding: 30px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
            height: auto;
        }
        .header h1 {
            font-size: 24px;
            margin-top: 10px;
            color: #6a1b9a; /* สีม่วง */
        }
        .appointment-details {
            font-size: 18px;
            margin-bottom: 30px;
        }
        .appointment-details label {
            font-weight: bold;
        }
        /* สีสำหรับสถานะต่างๆ */
        .status-accepted {
            font-size: 20px;
            color: #4caf50; /* สีเขียว */
            margin-bottom: 20px;
            text-align: center;
        }
        .status-waiting {
            font-size: 20px;
            color: #ff9800; /* สีส้ม */
            margin-bottom: 20px;
            text-align: center;
        }
        .status-declined {
            font-size: 20px;
            color: #f44336; /* สีแดง */
            margin-bottom: 20px;
            text-align: center;
        }
        .status-unknown {
            font-size: 20px;
            color: #9e9e9e; /* สีเทา */
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-print {
            display: block;
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #6a1b9a; /* สีม่วง */
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }
        .btn-print:hover {
            background-color: #5e2a91; /* สีม่วงเข้ม */
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <img src="hospital-logo.png" alt="โรงพยาบาลเกษมราษฎร์ ประชาชื่น">
            <h1>โรงพยาบาลเกษมราษฎร์ ประชาชื่น</h1>
        </div>

        <?php foreach ($bookings as $booking): ?>
    <?php $status = getStatusMessage($booking['status']); ?>
    <!-- แสดงสถานะการจอง -->
    <div class="status <?php echo $status['class']; ?>">
        <p><?php echo $status['text']; ?></p>
    </div>

    <div class="appointment-details">
        <p><label>รหัสการจอง:</label> <?php echo $booking['booked_id']; ?></p>
        <p><label>ชื่อผู้ป่วย:</label> <?php echo $booking['name_patient']; ?></p>
        <p><label>คลินิก:</label> <?php echo $clinics[$booking['clinic_id']]['name']; ?></p>
        <p><label>วันที่นัดหมาย:</label> <?php echo formatThaiDate($booking['date_booked']); ?></p>
        <p><label>เวลานัดหมาย:</label> <?php echo date("H:i", strtotime($time_slots[$booking['time_slot_id']]['start_time'])) . "น - " . date("H:i", strtotime($time_slots[$booking['time_slot_id']]['end_time'])) . "น"; ?></p>
        <p><label>แพทย์ผู้รับผิดชอบ:</label> <?php echo $booking['doctor_name']; ?></p>
    </div>

    <!-- แสดงปุ่มพิมพ์เฉพาะเมื่อสถานะเป็น accept -->
    <?php if ($booking['status'] === 'accept'): ?>
        <button class="btn-print" onclick="window.location.href='print_appointment.php?booked_id=<?php echo $booking['booked_id']; ?>'">พิมพ์ใบนัดหมาย</button>
    <?php endif; ?>
    <hr> <!-- แยกการจองแต่ละรายการ -->
<?php endforeach; ?>

    </div>

</body>
</html>
