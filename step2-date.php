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
    <title>เลือกวัน - การจองคิวแพทย์</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

        .progress {
            height: 5px;
            background-color: #e9ecef;
        }

        .progress-bar {
            background-color: #6f42c1;
            width: 50%;
            /* Set progress to 50% for step 2 */
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
            color: #fff;
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

        .btn-primary {
            background-color: #6f42c1;
            border: none;
            font-size: 1.1rem;
        }

        .btn-primary:hover {
            background-color: #5a2e91;
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

        .fc-daygrid-day.fc-day-disabled {
            background-color: #d3d3d3;
        }

        .fc-daygrid-day.fc-day-today {
            background-color: #d1e7dd;
        }

        .fc-daygrid-day.selected {
            background-color: #6f42c1;
            color: #fff;
            border-radius: 5px;
            position: relative;
        }

        .fc-daygrid-day.selected::after {
            content: '✔';
            color: #fff;
            font-size: 1.4em;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .fc-daygrid-day.unavailable {
            background-color: #f8d7da;
        }

        #calendar {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-next {
            float: right;
        }
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
            <div class="progress-step active" id="step1Indicator">2. เลือกวัน</div>
            <div class="progress-step">3. เลือกช่วงเวลา</div>
            <div class="progress-step">4. ยืนยัน</div>
        </div>

        <h2 class="mb-4 text-center" style="font-size: 2rem; color: #6f42c1;">เลือกวันนัดหมาย</h2>
        <div id='calendar'></div>
        <div class="mt-4">
            <h4>วันที่เลือก: <span id="selectedDate" class="selected-date">ยังไม่เลือกวันที่</span></h4>
            <form id="dateForm">
                <input type="hidden" id="selected_date" name="date">
                <button type="button" class="btn btn-primary btn-next" onclick="nextStep()">ถัดไป</button>
            </form>
        </div>
    </div>

    <script src='https://unpkg.com/fullcalendar@5.11.2/main.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var availableDays = [];
            var dayOfWeekSelected = '';
        
            // Retrieve clinicId from local storage
            var clinicId = localStorage.getItem('selectedClinic');
        
            if (!clinicId) {
                alert('Clinic ID not found. Please select a clinic first.');
                window.location.href = 'index.php'; // Redirect back to the first page or handle as needed
                return;
            }
        
            function fetchAvailableDays() {
                return fetch(`get_clinic_schedule.php?clinic_id=${clinicId}`)
                    .then(response => response.json())
                    .then(data => {
                        const daysOfWeek = {
                            "Sunday": 0,
                            "Monday": 1,
                            "Tuesday": 2,
                            "Wednesday": 3,
                            "Thursday": 4,
                            "Friday": 5,
                            "Saturday": 6
                        };
        
                        return data.map(day => daysOfWeek[day]);
                    });
            }
        
            fetchAvailableDays().then(days => {
                availableDays = days;
                console.log('Available Days:', availableDays);
        
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    height: 'auto',
                    contentHeight: 'auto',
                    headerToolbar: {
                        left: 'prev',
                        center: 'title',
                        right: 'next today'
                    },
                    dateClick: function (info) {
                        var today = new Date();
                        var selectedDate = new Date(info.dateStr);
                        var selectedDay = selectedDate.getDay();
        
                        if (selectedDate < today) {
                            alert('ไม่สามารถจองวันย้อนหลังจากวันปัจจุบันได้');
                            return;
                        }
        
                        var minBookingDate = new Date();
                        minBookingDate.setDate(today.getDate() + 3);
        
                        if (selectedDate < minBookingDate) {
                            alert('กรุณาจองล่วงหน้าอย่างน้อย 3 วัน');
                            return;
                        }
        
                        if (!availableDays.includes(selectedDay)) {
                            alert('กรุณาจองวันทำการ');
                            return;
                        }
        
                        document.querySelectorAll('.fc-daygrid-day').forEach(function (day) {
                            day.classList.remove('selected');
                        });
        
                        var formattedDate = formatToThaiDate(info.dateStr);
                        info.dayEl.classList.add('selected');
                        document.getElementById('selected_date').value = info.dateStr;
                        document.getElementById('selectedDate').textContent = formattedDate;
                    },
                    dayCellDidMount: function (info) {
                        var cellDate = new Date(info.date);
                        var today = new Date();
                        today.setHours(0, 0, 0, 0);
                        var cellDay = cellDate.getDay();
        
                        if (cellDate < today) {
                            info.el.style.pointerEvents = 'none';
                            info.el.style.backgroundColor = '#f0f0f0';
                            return;
                        }
        
                        if (!availableDays.includes(cellDay)) {
                            info.el.style.pointerEvents = 'none';
                            info.el.style.backgroundColor = '#e0e0e0';
                            info.el.style.color = '#b0b0b0';
                        }
                    }
                });
        
                calendar.render();
            });
        
            function formatToThaiDate(dateStr) {
                const months = [
                    'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                    'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
                ];
        
                const date = new Date(dateStr);
                const day = date.getDate();
                const month = months[date.getMonth()];
                const year = date.getFullYear() + 543;
        
                return `${day} ${month} ${year}`;
            }
        
            window.nextStep = function () {
                if (document.getElementById('selected_date').value === '') {
        alert('กรุณาเลือกวันที่');
    } else {
        // Store the selected date and day of the week
        const selectedDate = new Date(document.getElementById('selected_date').value);
        const dayOfWeek = selectedDate.getDay(); // Get day of the week (0-6)
        localStorage.setItem('selectedDate', document.getElementById('selected_date').value);
        localStorage.setItem('dayOfWeekSelected', dayOfWeek); // Save day of the week
        window.location.href = 'step3-time.php'; // Adjust URL as needed
    }
            };
        });
        </script>
        
</body>

</html>
