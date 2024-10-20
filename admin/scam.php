<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การแสกนรหัส - ระบบคลินิกทันตกรรม</title>
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
        h1 {
            font-size: 2rem;
            color: #007bff;
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-custom {
            background-color: #8e24aa;
            color: white;
        }
        .btn-custom:hover {
            background-color: #7b1fa2;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>แสกนรหัสการจอง</h1>
        <form id="scanForm" class="text-center">
            <input type="text" id="bookingCode" class="form-control" placeholder="กรอกรหัสการจอง" required>
            <button type="submit" class="btn btn-custom mt-3">แสกน</button>
        </form>

        <!-- Result Message -->
        <div id="resultMessage" class="alert alert-success mt-5" style="display: none;">
            ผู้ป่วยรหัสการจอง <span id="bookingId"></span> มาถึงแล้ว
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Function to simulate scanning and updating status
        $("#scanForm").on("submit", function(event) {
            event.preventDefault(); // ป้องกันการรีเฟรชหน้าเมื่อกดปุ่ม
            var bookingCode = $("#bookingCode").val(); // รับค่าจาก input
            // ทดสอบการแสดงข้อความว่าผู้ป่วยมาถึงแล้ว
            if (bookingCode !== "") {
                $("#bookingId").text(bookingCode); // แสดงรหัสการจองที่กรอก
                $("#resultMessage").show(); // แสดงข้อความว่าผู้ป่วยมาถึงแล้ว

                // เรียกใช้ SweetAlert2 แสดงข้อความยืนยัน
                Swal.fire({
                    title: "สำเร็จ!",
                    text: "ผู้ป่วยรหัสการจอง " + bookingCode + " มาถึงแล้ว",
                    icon: "success",
                    confirmButtonText: "ตกลง"
                });

                // อาจจะมีการอัปเดตสถานะในฐานข้อมูลผ่าน AJAX
                $.ajax({
                    url: "update_patient_status.php", // สคริปต์ PHP สำหรับอัปเดตสถานะ
                    type: "POST",
                    data: { bookingCode: bookingCode, status: "arrived" },
                    success: function(response) {
                        console.log("สถานะอัปเดตเรียบร้อย");
                    }
                });
            }
        });
    </script>

</body>
</html>
