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
        .btn-primary {
            background-color: #6f42c1;
            border: none;
            font-size: 1.1rem;
        }
        .btn-primary:hover {
            background-color: #5a2e91;
        }
        .progress-bar {
            background-color: #6f42c1;
            height: 5px;
        }
        .progress-steps {
            margin-bottom: 20px;
        }
        .progress-step {
            width: 25%;
            float: left;
            text-align: center;
            font-size: 1rem;
            position: relative;
        }
        .progress-step.active {
            color: #6f42c1;
        }
        .progress-step.completed {
            color: #6f42c1;
        }
        .progress-step::before {
            content: '';
            width: 24px;
            height: 24px;
            background-color: #fff;
            border: 2px solid #6f42c1;
            border-radius: 50%;
            display: inline-block;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: -14px;
            z-index: 1;
        }
        .progress-step.completed::before {
            background-color: #6f42c1;
            border: none;
        }
        .progress-step.completed::after {
            content: '✓';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: -12px;
            color: #fff;
            font-size: 14px;
        }
        .step-content {
            display: none;
        }
        .step-content.active {
            display: block;
        }
        .fc-header-toolbar {
            background-color: #6f42c1;
            color: #fff;
            border-radius: 8px 8px 0 0;
        }
        .fc-toolbar-title {
            font-size: 1.5em;
        }
        .fc-daygrid-day {
            border-radius: 5px;
        }
        .fc-daygrid-day:hover {
            background-color: #e9ecef;
        }
        .fc-event {
            background-color: #28a745;
            color: #fff;
            border: 1px solid #218838;
            border-radius: 5px;
        }
        .fc-event:hover {
            background-color: #1e7e34;
        }
        #calendar {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 768px) {
            .fc-col-header {
                font-size: 0.8em;
            }
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2 class="mb-4 text-center" style="font-size: 2rem; color: #6f42c1;">การจองคิวแพทย์</h2>

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
            <h3 class="text-center">เลือกวันนัดหมาย</h3>
            <div id='calendar'></div>
            <form id="dateForm" class="mt-4">
                <input type="hidden" id="selected_date" name="date">
                <button type="button" class="btn btn-primary" onclick="nextStep(3)">ถัดไป</button>
            </form>
        </div>

        <!-- Step 3: Time Slot Selection -->
        <div id="step3" class="step-content">
            <h3 class="text-center">เลือกช่วงเวลานัดหมาย</h3>
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
            <h3 class="text-center">ยืนยันการจอง</h3>
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
                eventColor: '#28a745',
                dateClick: function(info) {
                    selectedDate = info.dateStr;
                    document.querySelector('#dateForm input[name="date"]').value = selectedDate;
                }
            });

            calendar.render();

            // บังคับให้ FullCalendar คำนวณขนาดใหม่หลังจากการแสดงผล
            window.addEventListener('resize', function() {
                calendar.updateSize();
            });

            // บังคับให้ FullCalendar คำนวณขนาดใหม่เมื่อโหลด
            setTimeout(function() {
                calendar.updateSize();
            }, 100);
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
            $('#time_slot').empty();
            // Simulate time slots (Replace this with actual data from server)
            const timeSlots = ['09:00-10:00', '10:00-11:00', '11:00-12:00'];
            timeSlots.forEach(slot => {
                $('#time_slot').append(`<option value="${slot}">${slot}</option>`);
            });
        }

        function confirmBooking() {
            Swal.fire({
                title: 'ยืนยันการจอง',
                text: `คลินิก: ${selectedClinic}\nวันที่: ${selectedDate}\nช่วงเวลา: ${$('#time_slot').val()}`,
                icon: 'success',
                confirmButtonText: 'ตกลง'
            });
        }
    </script>
</body>
</html>
