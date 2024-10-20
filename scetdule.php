<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambulance Booking Calendar</title>
    <style>
        /* Custom CSS overrides */
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        #calendar {
            max-width: 900px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
    </style>
    <script src='assets/js/index.global.min.js'></script>
</head>
<body>

    <h1>Ap</h1>
    <div id='calendar'></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: 'fetch_events.php',  // เรียกใช้ข้อมูลการจองจาก PHP
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                eventColor: '#378006'
            });
            calendar.render();
        });
    </script>

</body>
</html>
