<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การตั้งค่า - ระบบคลินิกทันตกรรม</title>
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
        <h1 class="text-center mb-4" style="font-size: 2rem; color: #007bff;">การตั้งค่า</h1>

        <!-- General Settings -->
        <div class="card mb-4">
            <div class="card-header">
                การตั้งค่าทั่วไป
            </div>
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label for="siteName">ชื่อเว็บไซต์</label>
                        <input type="text" class="form-control" id="siteName" value="ระบบคลินิกทันตกรรม">
                    </div>
                    <div class="form-group">
                        <label for="siteEmail">อีเมลสำหรับการติดต่อ</label>
                        <input type="email" class="form-control" id="siteEmail" value="info@dentalclinic.com">
                    </div>
                    <div class="form-group">
                        <label for="sitePhone">เบอร์โทรศัพท์</label>
                        <input type="text" class="form-control" id="sitePhone" value="02-123-4567">
                    </div>
                    <button type="submit" class="btn btn-custom">บันทึกการตั้งค่า</button>
                </form>
            </div>
        </div>

        <!-- User Management -->
        <div class="card mb-4">
            <div class="card-header">
                การจัดการผู้ใช้
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>รหัสผู้ใช้</th>
                            <th>ชื่อผู้ใช้</th>
                            <th>อีเมล</th>
                            <th>บทบาท</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>admin</td>
                            <td>admin@dentalclinic.com</td>
                            <td>ผู้ดูแลระบบ</td>
                            <td><button class="btn btn-danger btn-sm">ลบ</button></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>staff</td>
                            <td>staff@dentalclinic.com</td>
                            <td>พนักงาน</td>
                            <td><button class="btn btn-danger btn-sm">ลบ</button></td>
                        </tr>
                        <!-- More rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="card mb-4">
            <div class="card-header">
                การตั้งค่าการแจ้งเตือน
            </div>
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <label for="notificationEmail">อีเมลสำหรับการแจ้งเตือน</label>
                        <input type="email" class="form-control" id="notificationEmail" value="notifications@dentalclinic.com">
                    </div>
                    <div class="form-group">
                        <label for="smsNotification">หมายเลขโทรศัพท์สำหรับการแจ้งเตือนทาง SMS</label>
                        <input type="text" class="form-control" id="smsNotification" value="081-234-5678">
                    </div>
                    <button type="submit" class="btn btn-custom">บันทึกการตั้งค่า</button>
                </form>
            </div>
        </div>
        
    </div>

    <footer class="mt-5">
        <p>&copy; 2024 โรงพยาบาลเกษมราษฎร์ ประชาชื่น | โทร: 02-123-4567</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
