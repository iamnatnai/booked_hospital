<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ปฏิทินการจอง - คลินิกทันตกรรม</title>
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
        .fc-event.accept {
            background-color: #28a745; /* สีเขียวสำหรับการอนุมัติ */
            color: #fff;
        }
        .fc-event.waiting {
            background-color: #6c757d; /* สีเทาสำหรับยังไม่อนุมัติ */
            color: #fff;
        }
        .fc-event:hover {
            opacity: 0.8;
        }
        .swal2-popup {
            font-size: 1.2rem; /* ปรับขนาดตัวอักษรของ SweetAlert */
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4" style="font-size: 2rem; color: #007bff;">ปฏิทินการจองคิวแพทย์</h2>

        <div id='calendar'></div>
    </div>

    <script src='https://unpkg.com/fullcalendar@5.11.2/main.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
        events: 'get_booking.php', // URL ที่ไปยังสคริปต์ PHP ของคุณ
        eventClick: function(info) {
    let event = info.event;
    let status = event.classNames.includes('accept') ? 'accept' : 'waiting';

    Swal.fire({
        title: event.title,
        text: event.extendedProps.description,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: status === 'waiting' ? 'อนุมัติ' : 'ล้มเลิกการอนุมัติ',
        cancelButtonText: 'ปิด',
        preConfirm: () => {
            // Update the event class based on the current status
            let newStatus = status === 'waiting' ? 'accept' : 'waiting';
            event.setProp('classNames', [newStatus]);

            // Make AJAX call to update the status in the database
            return $.ajax({
                type: "POST",
                url: "update_event_status.php", // URL of the PHP file
                data: {
                    id: event.id, // Event ID
                    status: newStatus // New status to update
                }
            }).done(function(response) {
                let result = JSON.parse(response);
                if (result.success) {
                    Swal.fire('สำเร็จ!', 'การจองนี้ได้รับการอนุมัติแล้ว.', 'success');
                } else {
                    Swal.fire('ผิดพลาด!', result.message, 'error');
                }
            }).fail(function() {
                Swal.fire('ผิดพลาด!', 'ไม่สามารถอัปเดตสถานะได้.', 'error');
            });
        }
    });
}

    });

    calendar.render();
});


    </script>
</body>
</html>
