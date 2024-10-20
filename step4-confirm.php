<?
session_start();

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
    <title>ยืนยันการจอง - การจองคิวแพทย์</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background-color: #f0f4f7; }
        .container { background-color: #fff; color: #333; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .btn-primary { background-color: #6f42c1; border: none; font-size: 1.1rem; }
        .btn-primary:hover { background-color: #5a2e91; }
        .progress-bar { background-color: #6f42c1; height: 5px; transition: width 0.3s; }
        .progress-steps { margin-bottom: 20px; }
        .progress-step { width: 25%; float: left; text-align: center; font-size: 1rem; position: relative; }
        .progress-step.active { color: #6f42c1; }
        .progress-step.completed { color: #6f42c1; }
        .progress-step::before { content: ''; width: 24px; height: 24px; background-color: #fff; border: 2px solid #6f42c1; border-radius: 50%; display: inline-block; position: absolute; left: 50%; transform: translateX(-50%); top: -14px; z-index: 1; }
        .progress-step.completed::before { background-color: #6f42c1; border: none; }
        .progress-step.completed::after { content: '✓'; position: absolute; left: 50%; transform: translateX(-50%); top: -12px; color: #fff; font-size: 14px; }
        .step-content { display: none; }
        .step-content.active { display: block; }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2 class="mb-4 text-center" style="font-size: 2rem; color: #6f42c1;">การจองคิวแพทย์</h2>

        <div class="progress mb-4">
            <div class="progress-bar" id="progressBar"></div>
        </div>
        <div class="progress-steps">
            <div class="progress-step completed">1. เลือกคลินิก</div>
            <div class="progress-step completed">2. เลือกวัน</div>
            <div class="progress-step completed">3. เลือกช่วงเวลา</div>
            <div class="progress-step active">4. ยืนยัน</div>
        </div>

        <!-- Step 4: Confirmation -->
        <div id="step4" class="step-content active">
            <h2 class="card-title">ข้อมูลผู้ป่วย</h2>
            <p>ชื่อ: <?php echo htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['last_name']); ?></p>
            <p>โทรศัพท์: <?php echo htmlspecialchars($_SESSION['phone_number']); ?></p>
            <h3 class="text-center">ยืนยันการจอง</h3>
            <p>คลินิก: <span id="confirm_clinic"></span></p>
            <p>วันที่: <span id="confirm_date"></span></p>
            <p>ช่วงเวลา: <span id="confirm_time"></span></p>
            <button type="button" class="btn btn-primary" onclick="confirmBooking()">ยืนยัน</button>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
    const clinicId = localStorage.getItem('selectedClinic');
    const date = localStorage.getItem('selectedDate');
    const timeSlotId = localStorage.getItem('selectedTime');
    console.log("Clinic ID:", clinicId);
console.log("Selected Date:", date);
console.log("Time Slot ID:", timeSlotId);
    // Fetch clinic details
    $.ajax({
    url: 'get_clinic.php',
    method: 'GET',
    data: { id: clinicId },
    success: function(response) {
        console.log(response); // ตรวจสอบข้อมูลใน console

        // ใช้ JSON.parse() เพื่อแปลง response เป็นวัตถุ JSON
        let data;
        try {
            data = JSON.parse(response);
        } catch (error) {
            console.error("Error parsing JSON:", error);
            $('#confirm_clinic').text('เกิดข้อผิดพลาดในการแปลงข้อมูล');
            return;
        }

        // ตรวจสอบว่ามีข้อมูลใน data
        if (data.th_clinicname && data.name) {
            $('#confirm_clinic').text(`${data.th_clinicname} (${data.name})`);
        } else {
            $('#confirm_clinic').text('ไม่พบข้อมูลคลินิก');
        }
    },
    error: function(xhr, status, error) {
        console.error("Error fetching clinic:", error);
        $('#confirm_clinic').text('เกิดข้อผิดพลาดในการดึงข้อมูลคลินิก');
    }
});


    // Format date to Thai format
    const thaiMonths = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
    const thaiDays = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
    const bookingDate = new Date(date);
    const thaiFormattedDate = `วัน${thaiDays[bookingDate.getDay()]}ที่ ${bookingDate.getDate()} ${thaiMonths[bookingDate.getMonth()]} ${bookingDate.getFullYear() + 543}`;
    $('#confirm_date').text(thaiFormattedDate);

    // Fetch time slot details
    $.ajax({
    url: 'get_time_slot.php',
    method: 'GET',
    data: { id: timeSlotId },
    success: function(response) {
        console.log(response); // ตรวจสอบข้อมูลใน console

        // ใช้ JSON.parse() เพื่อแปลง response เป็นวัตถุ JSON
        let data;
        try {
            data = JSON.parse(response);
        } catch (error) {
            console.error("Error parsing JSON:", error);
            $('#confirm_time').text('เกิดข้อผิดพลาดในการแปลงข้อมูล');
            return;
        }

        // ตรวจสอบว่ามีค่าช่วงเวลา (start_time, end_time)
        if (data.start_time && data.end_time) {
            const formattedStartTime = data.start_time.slice(0, 5); // ตัดเฉพาะ HH:MM
            const formattedEndTime = data.end_time.slice(0, 5);

            $('#confirm_time').text(`${formattedStartTime}น - ${formattedEndTime}น`);
        } else {
            $('#confirm_time').text('ไม่พบข้อมูลช่วงเวลา');
        }
    },
    error: function(xhr, status, error) {
        console.error("Error fetching time slot:", error);
        $('#confirm_time').text('เกิดข้อผิดพลาดในการดึงข้อมูลช่วงเวลา');
    }
});





    // อัปเดต Progress Bar เป็น 100%
    updateProgressBar(4);
});


// ฟังก์ชันสำหรับอัปเดต Progress Bar
function updateProgressBar(step) {
    const progressPercentage = (step - 1) * 33.33; // ขั้นตอนที่ 4 คิดเป็น 100%
    $('#progressBar').css('width', progressPercentage + '%');
}
function confirmBooking() {
    Swal.fire({
        title: 'ยืนยันการจอง',
        text: `คลินิก: ${$('#confirm_clinic').text()}\nวันที่: ${$('#confirm_date').text()}\nช่วงเวลา: ${$('#confirm_time').text()}`,
        icon: 'success',
        confirmButtonText: 'ตกลง',
        preConfirm: () => {
            const clinicId = localStorage.getItem('selectedClinic');
            const dateBooked = localStorage.getItem('selectedDate');
            const timeSlotId = localStorage.getItem('selectedTime');
            const patientName = `${<?php echo json_encode($_SESSION['first_name']); ?>} ${<?php echo json_encode($_SESSION['last_name']); ?>}`;
            const nationalId = <?php echo json_encode($_SESSION['national_id']); ?>; // รับข้อมูลหมายเลขประจำตัวประชาชน
            const phoneNumber = <?php echo json_encode($_SESSION['phone_number']); ?>; // รับหมายเลขโทรศัพท์

            // ส่งข้อมูลไปยัง PHP
            return $.ajax({
                url: 'confirm_booking.php', // เปลี่ยน URL เป็นสคริปต์ PHP ที่จะจัดการการยืนยันการจอง
                type: 'POST',
                data: {
                    clinic_for_book: clinicId,
                    date_booked: dateBooked,
                    time_slot_id: timeSlotId,
                    name_patient: patientName,
                    national_id: nationalId,
                    phone_number: phoneNumber
                }
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            localStorage.clear(); // เคลียร์ข้อมูลการจอง
            window.location.href = 'index.php'; // เปลี่ยนไปยังหน้าแรก
        }
    });
}

    </script>
</body>
</html>
