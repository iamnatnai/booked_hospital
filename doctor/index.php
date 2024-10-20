<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าแรก - โรงพยาบาลเกษมราษฎร์ ประชาชื่น</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.css">
    <style>
        body {
            background-color: #f3e6f8;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #8e24aa;
            color: white;
        }
        .btn-custom:hover {
            background-color: #7b1fa2;
        }
        .card {
            margin-bottom: 20px;
        }
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }
        .table th, .table td {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center">สรุปการทำงานและการนัดหมาย</h2>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-6">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">จำนวนวันที่ทำงาน</div>
                    <div class="card-body">
                        <h5 class="card-title" id="totalWorkDays">0 วัน</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">จำนวนวันที่เหลือ</div>
                    <div class="card-body">
                        <h5 class="card-title" id="remainingWorkDays">0 วัน</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graph -->
        <div class="card">
            <div class="card-header">กราฟสรุปการทำงาน</div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="workGraph"></canvas>
                </div>
            </div>
        </div>

        <!-- Patients with Remaining Appointments -->
        <div class="card mt-4">
            <div class="card-header">รายชื่อผู้ป่วยที่ยังมีการนัดหมาย</div>
            <div class="card-body">
                <table id="patientsTable" class="display">
                    <thead>
                        <tr>
                            <th>ชื่อผู้ป่วย</th>
                            <th>วันที่นัดหมาย</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ข้อมูลจะถูกโหลดที่นี่ -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Button to Select Work Days -->
        <div class="text-center mt-4">
            <a href="doctormeet.php" class="btn btn-custom">เลือกวันทำงาน</a>
        </div>
    </div>

    <!-- JS libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable for patients
            $('#patientsTable').DataTable({
                ajax: {
                    url: 'fetch_patients.php',
                    dataSrc: ''
                },
                columns: [
                    { data: 'patient_name' },
                    { data: 'appointment_date' }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Thai.json'
                }
            });

            // Mock data for the graph (replace with actual data from the server)
            var ctx = document.getElementById('workGraph').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['วันจันทร์', 'วันอังคาร', 'วันพุธ', 'วันพฤหัสบดี', 'วันศุกร์', 'วันเสาร์', 'วันอาทิตย์'],
                    datasets: [{
                        label: 'จำนวนวันที่ทำงาน',
                        data: [5, 6, 7, 6, 5, 3, 2], // Replace with actual data
                        backgroundColor: 'rgba(142, 36, 170, 0.6)'
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Example data for work days summary
            document.getElementById('totalWorkDays').textContent = '20 วัน';
            document.getElementById('remainingWorkDays').textContent = '5 วัน';
        });
    </script>
</body>
</html>
