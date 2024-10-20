<?session_start();

// ตรวจสอบว่าเซสชันของผู้ใช้มีค่าหรือไม่
if (!isset($_SESSION['citizen'])) {
    // ถ้าไม่มีค่าของเซสชัน ให้เปลี่ยนเส้นทางไปยังหน้า login
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบนัดคิวแพทย์ ทันตกรรม - โรงพยาบาลเกษมราษฎร์ ประชาชื่น</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f3f6f9;
            font-family: 'TH SarabunPSK', sans-serif;
            margin: 0;
            padding: 0;
        }

        .hero-section {
            background: url('dental_clinic_banner.jpg') no-repeat center center;
            background-size: cover;
            height: 400px;
            text-align: center;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #6a1b9a;
            border-bottom: 4px solid #8e24aa;
            position: relative;
            overflow: hidden;
        }

        .hero-section:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }

        .hero-section h1 {
            font-size: 3rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            z-index: 1;
        }

        .btn-custom {
            background-color: #8e24aa;
            color: white;
            border-radius: 25px;
            padding: 10px 20px;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-custom:hover {
            background-color: #7b1fa2;
        }

        footer {
            background-color: #6a1b9a;
            color: white;
            padding: 20px 0;
            border-top: 4px solid #8e24aa;
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .card-body {
            text-align: center;
        }

        .card-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: bold;
            color: #6a1b9a;
        }

        .card-text {
            font-size: 1rem;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>

    <div class="hero-section">
        <h1>ยินดีต้อนรับสู่โรงพยาบาลเกษมราษฎร์ ประชาชื่น</h1>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <?php if (isset($_SESSION['citizen'])): ?>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">ข้อมูลผู้ป่วย</h2>
                            <p class="card-text">ชื่อ: <?php echo htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['last_name']); ?></p>
                            <p class="card-text">National ID: ***-***-**<?php echo htmlspecialchars(substr($_SESSION['national_id'], -3)); ?></p>
                            <p class="card-text">โทรศัพท์: <?php echo htmlspecialchars($_SESSION['phone_number']); ?></p>
                            <a href="logout.php" class="btn btn-danger">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-center">กรุณาเข้าสู่ระบบเพื่อดูข้อมูลผู้ป่วย</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">นัดคิวแพทย์</h2>
                        <p class="card-text">เลือกเวลานัดหมายแพทย์และกรอกข้อมูลผู้ป่วย</p>
                        <a href="step1-clinic.php" class="btn btn-custom">นัดคิวทันที</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">ตารางเวลาแพทย์</h2>
                        <p class="card-text">ตรวจสอบเวลาการเข้ารับการรักษาของแพทย์</p>
                        <a href="schedule.php" class="btn btn-custom">ดูตารางเวลา</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">ตารางนัดเวลา</h2>
                        <p class="card-text">กดที่นี่เพื่อดูสถานะคิวของท่านและทำการพิมพ์ใบเสร็จ</p>
                        <a href="approve.php" class="btn btn-custom">ดูสถานะคิว</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center mt-5">
        <p>&copy; 2024 โรงพยาบาลเกษมราษฎร์ ประชาชื่น | โทร: 02-123-4567</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
