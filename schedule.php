<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตารางเวลาแพทย์ - โรงพยาบาลเกษมราษฎร์ ประชาชื่น</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <style>
        body {
            background-color: #f3e6f8; /* สีพื้นหลังเบา ๆ */
        }
        .container {
            background-color: #ffffff; /* สีพื้นหลังของ container */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #8e24aa; /* สีม่วงเข้ม */
            color: white;
        }
        .btn-custom:hover {
            background-color: #7b1fa2; /* สีม่วงเข้มกว่าหมายถึงสถานะ Hover */
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center">ตารางเวลาแพทย์</h2>
        <table id="scheduleTable" class="display">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>เวลาเริ่มต้น</th>
                    <th>เวลาสิ้นสุด</th>
                    <th>แพทย์</th>
                </tr>
            </thead>
            <tbody>
                <!-- ข้อมูลจะถูกโหลดที่นี่ -->
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-custom">กลับสู่หน้าหลัก</a>
        </div>
    </div>

    <!-- JS libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#scheduleTable').DataTable({
                ajax: {
                    url: 'fetch_schedule.php',
                    dataSrc: ''
                },
                columns: [
                    { data: 'date' },
                    { data: 'start_time' },
                    { data: 'end_time' },
                    { data: 'doctor_name' }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Thai.json'
                }
            });
        });
    </script>
</body>
</html>
