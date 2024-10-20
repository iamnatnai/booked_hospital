<?php
session_start();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การจองคิวแพทย์ - คลินิกทันตกรรม</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href='https://unpkg.com/fullcalendar@5.11.2/main.min.css' rel='stylesheet' />
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #6a1b9a; /* สีม่วงเข้ม */
            color: white;
            padding: 30px;
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #8e24aa;
            border: none;
            font-size: 1.1rem;
        }
        .btn-primary:hover {
            background-color: #7b1fa2;
        }
        .progress-bar {
            background-color: #8e24aa; /* สีม่วงเข้ม */
            height: 5px;
        }
        .progress-step {
            width: 25%;
            float: left;
            position: relative;
            text-align: center;
        }
        .progress-step.active {
            color: #8e24aa;
        }
        .progress-step.completed {
            color: #8e24aa;
        }
        .progress-step::before {
            content: '';
            width: 20px;
            height: 20px;
            background-color: #fff;
            border: 2px solid #8e24aa;
            border-radius: 50%;
            display: inline-block;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: -10px;
            z-index: 1;
        }
        .progress-step.completed::before {
            background-color: #8e24aa;
            border: none;
        }
        .progress-step.completed::after {
            content: '✓';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: -8px;
            color: #fff;
        }
        .step-content {
            display: none;
        }
        .step-content.active {
            display: block;
        }
        .fc-header-toolbar {
            background-color: #4a90e2;
            color: #ffffff;
            border-radius: 8px 8px 0 0;
        }
        .fc-toolbar-title {
            font-size: 1.5em;
        }
        .fc-daygrid-day {
            border-radius: 5px;
        }
        .fc-daygrid-day:hover {
            background-color: #e0e0e0;
        }
        .fc-event {
            background-color: #378006;
            color: #ffffff;
            border: 1px solid #005500;
            border-radius: 5px;
        }
        .fc-event:hover {
            background-color: #004d00;
        }
        #calendar {
    max-width: 900px;
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
.fc-col-header-cell-cushion {
    z-index: 1; /* ปรับ z-index เพื่อให้ปุ่มอยู่ด้านบน */
    position: relative; /* ให้แน่ใจว่า position เป็น relative */
}

.fc-daygrid-day-number {
    position: relative; /* ป้องกันการทับซ้อนกับองค์ประกอบอื่น */
}

/* ตัวอย่าง CSS ที่ควรหลีกเลี่ยงการทับซ้อนกัน */
.fc-col-header-cell, .fc-col-header-cell-cushion {
    z-index: 1;
}

    </style>
</head>
<body>

    <div class="container mt-5">
        <h2 class="mb-4" style="font-size: 2rem;">การจองคิวแพทย์</h2>

        <div class="progress">
            <div class="progress-bar"></div>
        </div>
        <div class="progress-steps">
            <div class="progress-step active">1. เลือกคลินิก</div>
            <div class="progress-step">2. เลือกวัน</div>
            <div class="progress-step">3. เลือกช่วงเวลา</div>
            <div class="progress-step">4. ยืนยัน</div>
        </div>

        <!-- Step 1: Clinic Selection -->
        <div id="step1" class="step-content active">
            <form id="clinicForm">
                <div class="form-group">
                    <label for="clinic">เลือกคลินิก:</label>
                    <select id="clinic" name="clinic" class="form-control" required>
                        <option value="dental">ทันตกรรม</option>
                        <!-- เพิ่มคลินิกอื่น ๆ ที่นี่ -->
                    </select>
                </div>
                <button type="button" class="btn btn-primary" onclick="nextStep(2)">ถัดไป</button>
            </form>
        </div>

        <!-- Step 2: Date Selection -->
        <div id="step2" class="step-content">
            <h3>เลือกวันนัดหมาย</h3>
            <div id='calendar'></div>
            <form id="dateForm" class="mt-4">
                <input type="hidden" id="selected_date" name="date">
                <button type="button" class="btn btn-primary" onclick="nextStep(3)">ถัดไป</button>
            </form>
        </div>

        <!-- Step 3: Time Slot Selection -->
        <div id="step3" class="step-content">
            <h3>เลือกช่วงเวลานัดหมาย</h3>
            <form id="timeForm">
                <input type="hidden" name="date">
                <div class="form-group">
                    <label for="time_slot">เลือกช่วงเวลา:</label>
                    <select id="time_slot" name="time_slot" class="form-control" required>
                        <!-- Time slots will be populated dynamically -->
                    </select>
                </div>
                <button type="button" class="btn btn-primary" onclick="nextStep(4)">ยืนยัน</button>
            </form>
        </div>

        <!-- Step 4: Confirmation -->
        <div id="step4" class="step-content">
            <h3>ยืนยันการจอง</h3>
            <p>ข้อมูลการจองของคุณ:</p>
            <p id="confirmation_details"></p>
            <button type="button" class="btn btn-primary" onclick="confirmBooking()">ยืนยัน</button>
        </div>
    </div>

    <script src='https://unpkg.com/fullcalendar@5.11.2/main.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let selectedClinic = '';
        let selectedDate = '';
        let selectedTimeSlot = '';

        document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        contentHeight: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventColor: '#378006'
    });
    calendar.render();

    // บังคับให้ FullCalendar คำนวณขนาดใหม่
    window.addEventListener('resize', function() {
        calendar.updateSize();
    });
});



        function nextStep(step) {
            $('.step-content').removeClass('active');
            $('.progress-step').removeClass('active').removeClass('completed');
            $('#step' + step).addClass('active');
            $('.progress-step:nth-child(' + step + ')').addClass('active').addClass('completed');

            if (step === 2) {
                selectedClinic = $('#clinic').val();
                document.querySelector('#dateForm input[name="date"]').value = selectedDate;
                // Fetch available time slots
                fetchTimeSlots(selectedDate);
            } else if (step === 3) {
                document.querySelector('#timeForm input[name="date"]').value = selectedDate;
                $('#confirmation_details').text(`คลินิก: ${selectedClinic}, วันที่: ${selectedDate}, ช่วงเวลา: ${$('#time_slot').val()}`);
            }
        }

        function fetchTimeSlots(date) {
            // Fetch available time slots based on the selected date
            // This is a placeholder. You should replace this with actual AJAX call to fetch time slots.
            $('#time_slot').html('<option value="09:00-10:00">09:00-10:00</option><option value="10:00-11:00">10:00-11:00</option>');
        }

        function confirmBooking() {
            // Implement confirmation logic here (e.g., send data to the server)
            Swal.fire({
                title: 'ยืนยันการจอง?',
                text: 'คุณต้องการจองคิวนี้หรือไม่?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the booking form
                    const formData = {
                        clinic: selectedClinic,
                        date: selectedDate,
                        time_slot: $('#time_slot').val()
                    };

                    $.ajax({
                        url: 'submit_appointment.php',
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            Swal.fire('สำเร็จ!', 'การจองของคุณถูกส่งเรียบร้อยแล้ว.', 'success');
                        },
                        error: function() {
                            Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถส่งการจองได้. โปรดลองอีกครั้ง.', 'error');
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>
