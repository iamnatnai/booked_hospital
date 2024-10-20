<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// เชื่อมต่อฐานข้อมูล (PDO)
include('config.php');

// ตรวจสอบว่ามีการส่งค่า booked_id หรือไม่
if (isset($_GET['booked_id'])) {
    $booked_id = $_GET['booked_id'];

    // ดึงข้อมูลจากฐานข้อมูลตาม booked_id
    $sql = "SELECT booked_id, name_patient, clinic_id, time_slot_id, date_booked, doctor_name, status 
            FROM booked_confirm 
            WHERE booked_id = :booked_id";
    
    // เตรียมคำสั่ง SQL และผูกค่าตัวแปร
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':booked_id', $booked_id, PDO::PARAM_STR);
    $stmt->execute();

    // ดึงข้อมูลการจอง
    $booking = $stmt->fetch(PDO::FETCH_ASSOC); 
    if ($booking) {
        $booking_id = $booking['booked_id'];
        $patient_name = $booking['name_patient'];
        $clinic_id = $booking['clinic_id'];
        $time_slot_id = $booking['time_slot_id'];
        $appointment_date = $booking['date_booked'];
        $doctor_name = $booking['doctor_name'];
    } else {
        echo "ไม่พบข้อมูลการจองนี้";
        exit();
    }

    // ดึงข้อมูลคลินิก
    $clinic_stmt = $pdo->prepare("SELECT name FROM clinics WHERE id = :clinic_id");
    $clinic_stmt->bindParam(':clinic_id', $clinic_id);
    $clinic_stmt->execute();
    $clinic = $clinic_stmt->fetch(PDO::FETCH_ASSOC);
    $clinic_name = $clinic ? $clinic['name'] : 'ไม่ทราบชื่อคลินิก';

    // ดึงข้อมูลช่วงเวลา
    $time_slot_stmt = $pdo->prepare("SELECT start_time, end_time FROM time_slots WHERE id = :time_slot_id");
    $time_slot_stmt->bindParam(':time_slot_id', $time_slot_id);
    $time_slot_stmt->execute();
    $time_slot = $time_slot_stmt->fetch(PDO::FETCH_ASSOC);
    $appointment_time = $time_slot ? $time_slot['start_time'] . ' - ' . $time_slot['end_time'] : 'ไม่ทราบเวลานัด';

} else {
    echo "ไม่พบค่า booked_id";
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบนัดหมายแพทย์ - โรงพยาบาลเกษมราษฎร์ ประชาชื่น</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'TH SarabunPSK', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            color: #000000;
        }
        .print-container {
            width: 100%;
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            border: 2px solid #000;
            border-radius: 10px;
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            width: 100px;
            height: auto;
        }
        .header h1 {
            font-size: 24px;
            margin-top: 10px;
        }
        .hospital-info {
            font-size: 16px;
            text-align: center;
            margin-top: 10px;
        }
        .appointment-details {
            margin-top: 30px;
            font-size: 18px;
        }
        .appointment-details label {
            font-weight: bold;
        }
        .barcode {
            text-align: center;
            margin-top: 20px;
        }
        .signature-area {
            margin-top: 50px;
            text-align: right;
        }
        .signature-area p {
            font-size: 18px;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 200px;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 14px;
        }
        .btn-print {
            font-size: 1.5rem;
            padding: 15px 30px;
            border-radius: 5px;
        }
        @media print {
            .btn-print {
                display: none;
            }
        }
        </style>
</head>
<body>

    <div class="print-container">
        <!-- Hospital Logo and Header -->
        <div class="header">
            <img src="hospital-logo.png" alt="โรงพยาบาลเกษมราษฎร์ ประชาชื่น">
            <h1>โรงพยาบาลเกษมราษฎร์ ประชาชื่น</h1>
            <p class="hospital-info">ที่อยู่: 950 ถนนประชาชื่น แขวงวงศ์สว่าง เขตบางซื่อ กรุงเทพฯ 10800 <br> โทร: 02-123-4567</p>
        </div>

        <!-- Appointment Details -->
        <div class="appointment-details">
            <p><label>รหัสการจอง:</label> <?php echo htmlspecialchars($booking_id); ?></p>
            <p><label>ชื่อผู้ป่วย:</label> <?php echo htmlspecialchars($patient_name); ?></p>
            <p><label>คลินิก:</label> <?php echo htmlspecialchars($clinic_name); ?></p>
            <p><label>วันที่นัดหมาย:</label> <?php echo htmlspecialchars($appointment_date); ?></p>
            <p><label>เวลานัดหมาย:</label> <?php echo htmlspecialchars($appointment_time); ?></p>
            <p><label>แพทย์ผู้รับผิดชอบ:</label> <?php echo htmlspecialchars($doctor_name); ?></p>
        </div>

        <!-- Barcode Section -->
        <div class="barcode">
            <svg id="barcode"></svg>
            <p>รหัสการจอง: <?php echo htmlspecialchars($booking_id); ?></p>
        </div>

        <!-- Signature Area -->
        <div class="signature-area">
            <p>ลงชื่อแพทย์ผู้อนุมัติ: <span class="signature-line"></span></p>
            <p>วันที่: <span class="signature-line"></span></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>โปรดมาถึงโรงพยาบาลก่อนเวลานัดหมาย 15 นาที หากต้องการเลื่อนหรือยกเลิกการนัด โปรดติดต่อที่หมายเลข 02-123-4567</p>
        </div>
    </div>

    <!-- Print Button -->
    <div class="text-center">
        <button class="btn btn-primary btn-print" onclick="window.print()">พิมพ์ใบนัด</button>
    </div>

    <!-- JsBarcode Script -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script>
        JsBarcode("#barcode", "<?php echo htmlspecialchars($booking_id); ?>", {
            format: "CODE128",
            displayValue: true,
            width: 2,
            height: 60,
            margin: 10
        });
    </script>

</body>
</html>