<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานการจอง - ระบบคลินิกทันตกรรม</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background-color: #f0f4f7;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #6a1b9a;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        footer {
            background-color: #6a1b9a; /* สีม่วงเข้มสำหรับพื้นหลังของ footer */
            color: white;
            padding: 10px 0;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center mb-4" style="font-size: 2rem; color: #007bff;">รายงานการจอง</h1>

        <!-- Report Card for Booking Status -->
        <div class="card mb-4">
            <div class="card-header">
                สถานะการจอง
            </div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>รหัสการจอง</th>
                            <th>ชื่อผู้ป่วย</th>
                            <th>แผนก</th>
                            <th>วันที่นัดหมาย</th>
                            <th>เวลานัดหมาย</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>A123456</td>
                            <td>นายสมชาย ใจดี</td>
                            <td>ทันตกรรม</td>
                            <td>14 กันยายน 2024</td>
                            <td>09:00 - 10:00</td>
                            <td>อนุมัติ</td>
                        </tr>
                        <tr>
                            <td>A123457</td>
                            <td>นางสาวสุนีย์ หมั่นดี</td>
                            <td>ทันตกรรม</td>
                            <td>14 กันยายน 2024</td>
                            <td>10:00 - 11:00</td>
                            <td>ยังไม่อนุมัติ</td>
                        </tr>
                        <tr>
                            <td>A123458</td>
                            <td>นายกิตติพงษ์ เพียรดี</td>
                            <td>ทันตกรรม</td>
                            <td>14 กันยายน 2024</td>
                            <td>11:00 - 12:00</td>
                            <td>อนุมัติ</td>
                        </tr>
                        <!-- More rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Report Card for Attendance -->
        <div class="card mb-4">
            <div class="card-header">
                รายงานการเข้ารับการรักษา
            </div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>รหัสการจอง</th>
                            <th>ชื่อผู้ป่วย</th>
                            <th>แผนก</th>
                            <th>วันที่เข้ารับการรักษา</th>
                            <th>เวลาที่เข้ารับการรักษา</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>A123456</td>
                            <td>นายสมชาย ใจดี</td>
                            <td>ทันตกรรม</td>
                            <td>14 กันยายน 2024</td>
                            <td>09:00 - 10:00</td>
                            <td>มาถึงแล้ว</td>
                        </tr>
                        <tr>
                            <td>A123457</td>
                            <td>นางสาวสุนีย์ หมั่นดี</td>
                            <td>ทันตกรรม</td>
                            <td>14 กันยายน 2024</td>
                            <td>10:00 - 11:00</td>
                            <td>ยังไม่มาถึง</td>
                        </tr>
                        <tr>
                            <td>A123458</td>
                            <td>นายกิตติพงษ์ เพียรดี</td>
                            <td>ทันตกรรม</td>
                            <td>14 กันยายน 2024</td>
                            <td>11:00 - 12:00</td>
                            <td>มาถึงแล้ว</td>
                        </tr>
                        <!-- More rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Additional Cards for Other Reports can be added here -->

    </div>

    <footer class="mt-5">
        <p>&copy; 2024 โรงพยาบาลเกษมราษฎร์ ประชาชื่น | โทร: 02-123-4567</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
