<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบควบคุมของผู้ดูแล - โรงพยาบาลเกษมราษฎร์ ประชาชื่น</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f7;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            color: #333;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #6a1b9a; /* สีม่วงเข้ม */
            color: #fff;
            font-size: 1.25rem;
        }
        .btn-custom {
            background-color: #8e24aa; /* สีม่วงเข้ม */
            color: white;
        }
        .btn-custom:hover {
            background-color: #7b1fa2; /* สีม่วงเข้มกว่าหมายถึงสถานะ Hover */
        }
        footer {
            background-color: #6a1b9a; /* สีม่วงเข้มสำหรับพื้นหลังของ footer */
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .summary-card {
            background-color: #8e24aa; /* สีม่วงเข้ม */
            color: white;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center mb-4" style="font-size: 2rem; color: #007bff;">ระบบควบคุมของผู้ดูแล</h1>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="card-header">
                        การจองทั้งหมด
                    </div>
                    <div class="card-body">
                        <h2 class="text-center">120</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="card-header">
                        ผู้ป่วยใหม่
                    </div>
                    <div class="card-body">
                        <h2 class="text-center">50</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="card-header">
                        การยกเลิก
                    </div>
                    <div class="card-body">
                        <h2 class="text-center">10</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        กราฟการจอง
                    </div>
                    <div class="card-body">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        กราฟผู้ป่วยใหม่
                    </div>
                    <div class="card-body">
                        <canvas id="newPatientsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        ตารางการจอง
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>HN</th>
                                    <th>ชื่อผู้ป่วย</th>
                                    <th>วันที่จอง</th>
                                    <th>สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>001</td>
                                    <td>นายสมชาย ใจดี</td>
                                    <td>2024-09-20</td>
                                    <td>สำเร็จ</td>
                                </tr>
                                <tr>
                                    <td>002</td>
                                    <td>นางสาวสาวิตรี สวยงาม</td>
                                    <td>2024-09-21</td>
                                    <td>รอดำเนินการ</td>
                                </tr>
                                <tr>
                                    <td>003</td>
                                    <td>นายประจักษ์ โชคดี</td>
                                    <td>2024-09-22</td>
                                    <td>ยกเลิก</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        การจัดการการจอง
                    </div>
                    <div class="card-body">
                        <p>ดูและจัดการการจองทั้งหมดในระบบ</p>
                        <a href="manage_bookings.php" class="btn btn-custom">จัดการการจอง</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        ดูรายงาน
                    </div>
                    <div class="card-body">
                        <p>ตรวจสอบรายงานการจองและการใช้งานต่างๆ</p>
                        <a href="reports.php" class="btn btn-custom">ดูรายงาน</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        การตั้งค่าระบบ
                    </div>
                    <div class="card-body">
                        <p>ปรับแต่งการตั้งค่าต่างๆ ของระบบ</p>
                        <a href="set_time.php" class="btn btn-custom">ตั้งค่าตารางแพทย์</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5">
        <p>&copy; 2024 โรงพยาบาลเกษมราษฎร์ ประชาชื่น | โทร: 02-123-4567</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // กำหนดข้อมูลสำหรับกราฟการจอง
        const ctxBooking = document.getElementById('bookingChart').getContext('2d');
        const bookingChart = new Chart(ctxBooking, {
            type: 'line',
            data: {
                labels: ['สัปดาห์ 1', 'สัปดาห์ 2', 'สัปดาห์ 3', 'สัปดาห์ 4'],
                datasets: [{
                    label: 'การจอง',
                    data: [30, 40, 25, 60],
                    backgroundColor: 'rgba(142, 36, 170, 0.2)',
                    borderColor: 'rgba(142, 36, 170, 1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // กำหนดข้อมูลสำหรับกราฟผู้ป่วยใหม่
        const ctxNewPatients = document.getElementById('newPatientsChart').getContext('2d');
        const newPatientsChart = new Chart(ctxNewPatients, {
            type: 'bar',
            data: {
                labels: ['สัปดาห์ 1', 'สัปดาห์ 2', 'สัปดาห์ 3', 'สัปดาห์ 4'],
                datasets: [{
                    label: 'ผู้ป่วยใหม่',
                    data: [10, 15, 20, 25],
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
