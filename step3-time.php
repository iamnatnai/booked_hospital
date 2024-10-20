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
    <title>เลือกช่วงเวลา - การจองคิวแพทย์</title>
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
            <div class="progress-step active">3. เลือกช่วงเวลา</div>
            <div class="progress-step">4. ยืนยัน</div>
        </div>

        <!-- Step 3: Time Slot Selection -->
        <div id="step3" class="step-content active">
            <h3 class="text-center">เลือกช่วงเวลานัดหมาย</h3>
            <form id="timeForm" class="mt-4">
                <div class="form-group">
                    <label for="time_slot">ช่วงเวลา:</label>
                    <select id="time_slot" name="time_slot" class="form-control" required>
                        <!-- Time slots will be populated here -->
                    </select>
                </div>
                <button type="button" class="btn btn-primary" onclick="nextStep()">ถัดไป</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Define days of the week mapping
        const daysOfWeek = {
            0: 'Sunday',
            1: 'Monday',
            2: 'Tuesday',
            3: 'Wednesday',
            4: 'Thursday',
            5: 'Friday',
            6: 'Saturday'
        };

        // Function to update the progress bar
        function updateProgressBar(step) {
            const progressPercentage = (step - 1) * 33.33; // Adjust based on 4 steps
            $('#progressBar').css('width', progressPercentage + '%');
        }

        // Function to populate the time slots
        function populateTimeSlots(slots) {
            const select = $('#time_slot');
            select.empty(); // Clear any existing options
            if (slots.length > 0) {
                slots.forEach(slot => {
                    const option = $('<option></option>').val(slot.id)
                        .text(`ช่วงเวลา ${slot.start_time} - ${slot.end_time} (เปิดรับ: ${slot.booked_capacity})`);
                    select.append(option);
                });
            } else {
                const noSlotOption = $('<option></option>').val('').text('ไม่มีช่วงเวลาว่าง');
                select.append(noSlotOption);
            }
        }

        // Load available time slots when the page is ready
        $(document).ready(function() {
            updateProgressBar(3); // Update progress bar to step 3

            const clinicId = localStorage.getItem('selectedClinic'); // Retrieve clinic_id from localStorage
            const dayOfWeek = localStorage.getItem('dayOfWeekSelected'); // Retrieve dayOfWeekSelected from localStorage

            if (!clinicId || !dayOfWeek) {
                alert('กรุณาเลือกคลินิกและวันที่ก่อนหน้านี้');
                return;
            }

            // Convert dayOfWeek to integer if necessary
            const dayOfWeekInt = parseInt(dayOfWeek, 10);

            if (isNaN(dayOfWeekInt) || dayOfWeekInt < 0 || dayOfWeekInt > 6) {
                alert('วันที่ไม่ถูกต้อง');
                return;
            }

            const dayName = daysOfWeek[dayOfWeekInt];

            $.ajax({
                url: 'get_available_time_slots.php',
                method: 'GET',
                data: { clinic_id: clinicId, day_of_week: dayName },
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        console.error(data.error);
                        alert('เกิดข้อผิดพลาดในการโหลดข้อมูลช่วงเวลา');
                    } else {
                        populateTimeSlots(data);
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูลช่วงเวลา');
                }
            });
        });

        function nextStep() {
            const selectedTime = $('#time_slot').val();
            if (selectedTime) {
                localStorage.setItem('selectedTime', selectedTime);
                window.location.href = 'step4-confirm.php'; // Redirect to confirmation step
            } else {
                alert('กรุณาเลือกช่วงเวลา');
            }
        }
    </script>
</body>
</html>
