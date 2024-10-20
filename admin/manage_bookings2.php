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
        .fc-event.approved {
            background-color: #28a745; /* สีเขียวสำหรับการอนุมัติ */
            color: #fff;
        }
        .fc-event.pending {
            background-color: #6c757d; /* สีเทาสำหรับยังไม่อนุมัติ */
            color: #fff;
        }
        .fc-event:hover {
            opacity: 0.8;
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
                events: [
                    // Saturday, September 14, 2024
                    { id: '1', title: 'จองคิว: นายสมชาย ใจดี', start: '2024-09-14T09:00:00', end: '2024-09-14T09:30:00', description: 'คลินิก: ทันตกรรม\nช่วงเวลา: 09:00-09:30\nโทร: 081-234-5678\nรหัสบัตรประชาชน: 1234567890123', className: 'pending' },
                    { id: '2', title: 'จองคิว: นางสาวสุนีย์ หมั่นดี', start: '2024-09-14T09:30:00', end: '2024-09-14T10:00:00', description: 'คลินิก: ทันตกรรม\nช่วงเวลา: 09:30-10:00\nโทร: 082-345-6789\nรหัสบัตรประชาชน: 2345678901234', className: 'approved' },
                    { id: '3', title: 'จองคิว: นายกิตติพงษ์ เพียรดี', start: '2024-09-14T10:00:00', end: '2024-09-14T10:30:00', description: 'คลินิก: ทันตกรรม\nช่วงเวลา: 10:00-10:30\nโทร: 083-456-7890\nรหัสบัตรประชาชน: 3456789012345', className: 'pending' },
                    { id: '4', title: 'จองคิว: นางสาวเกษร วงศ์สวัสดิ์', start: '2024-09-14T10:30:00', end: '2024-09-14T11:00:00', description: 'คลินิก: ทันตกรรม\nช่วงเวลา: 10:30-11:00\nโทร: 084-567-8901\nรหัสบัตรประชาชน: 4567890123456', className: 'approved' },
                    { id: '5', title: 'จองคิว: นายธนากร สิทธิ์ดี', start: '2024-09-14T11:00:00', end: '2024-09-14T11:30:00', description: 'คลินิก: ทันตกรรม\nช่วงเวลา: 11:00-11:30\nโทร: 085-678-9012\nรหัสบัตรประชาชน: 5678901234567', className: 'pending' },
                    { id: '6', title: 'จองคิว: นางสาวยุพดี เจริญสุข', start: '2024-09-14T11:30:00', end: '2024-09-14T12:00:00', description: 'คลินิก: ทันตกรรม\nช่วงเวลา: 11:30-12:00\nโทร: 086-789-0123\nรหัสบัตรประชาชน: 6789012345678', className: 'approved' },
                    { id: '7', title: 'จองคิว: นายสมพร สุขใจ', start: '2024-09-14T12:00:00', end: '2024-09-14T12:30:00', description: 'คลินิก: ทันตกรรม\nช่วงเวลา: 12:00-12:30\nโทร: 087-890-1234\nรหัสบัตรประชาชน: 7890123456789', className: 'pending' },
                    { id: '8', title: 'จองคิว: นางสาวสิตานันท์ รุ่งเรือง', start: '2024-09-14T12:30:00', end: '2024-09-14T13:00:00', description: 'คลินิก: ทันตกรรม\nช่วงเวลา: 12:30-13:00\nโทร: 088-901-2345\nรหัสบัตรประชาชน: 8901234567890', className: 'approved' },
                    { id: '9', title: 'จองคิว: นายอัครเดช พลดี', start: '2024-09-14T13:00:00', end: '2024-09-14T13:30:00', description: 'คลินิก: ทันตกรรม\nช่วงเวลา: 13:00-13:30\nโทร: 089-012-3456\nรหัสบัตรประชาชน: 9012345678901', className: 'pending' },
                    { id: '10', title: 'จองคิว: นางสาวพิมพ์ชนก พรหมรักษ์', start: '2024-09-14T13:30:00', end: '2024-09-14T14:00:00', description: 'คลินิก: ทันตกรรม\nช่วงเวลา: 13:30-14:00\nโทร: 090-123-4567\nรหัสบัตรประชาชน: 0123456789012', className: 'approved' },
                    { id: '11', title: 'จองคิว: นายภานุพงษ์ ภัทรธรรม', start: '2024-09-14T14:00:00', end: '2024-09-14T14:30:00', description: 'คลินิก: ทันตกรรม\nช่วงเวลา: 14:00-14:30\nโทร: 091-234-5678\nรหัสบัตรประชาชน: 1234567890123', className: 'pending' }
                ],
                eventClick: function(info) {
                    let event = info.event;
                    let status = event.classNames.includes('approved') ? 'approved' : 'pending';

                    Swal.fire({
                        title: event.title,
                        text: event.extendedProps.description,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: status === 'pending' ? 'อนุมัติ' : 'ล้มเลิกการอนุมัติ',
                        cancelButtonText: 'ปิด',
                        preConfirm: () => {
                            if (status === 'pending') {
                                // Update the status to approved
                                event.setProp('classNames', ['approved']);
                                Swal.fire('สำเร็จ!', 'การจองนี้ได้รับการอนุมัติแล้ว.', 'success');
                            } else {
                                // Update the status to pending
                                event.setProp('classNames', ['pending']);
                                Swal.fire('สำเร็จ!', 'การอนุมัติของการจองนี้ถูกล้มเลิก.', 'success');
                            }
                        }
                    });
                }
            });

            calendar.render();
        });
    </script>
</body>
</html>
