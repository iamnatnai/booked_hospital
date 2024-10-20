<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Clinic Schedule - โรงพยาบาลเกษมราษฎร์ ประชาชื่น</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <style>
        body {
            background-color: #f7f8fc;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #5e35b1;
            font-weight: bold;
        }
        label {
            color: #5e35b1;
        }
        .btn-primary, .btn-secondary {
            background-color: #5e35b1;
            border: none;
        }
        .btn-primary:hover, .btn-secondary:hover {
            background-color: #4a148c;
        }
        .table {
            margin-top: 20px;
        }
        .table th {
            background-color: #5e35b1;
            color: white;
        }
        .fa-hospital {
            font-size: 24px;
            color: #5e35b1;
        }
        .icon-clinic {
            display: inline-block;
            width: 100%;
            text-align: center;
            padding: 15px 0;
            margin-bottom: 10px;
            font-size: 28px;
            color: #4a148c;
        }
        .icon-clinic i {
            margin-bottom: 5px;
        }
        .custom-switch .custom-control-label::before {
            background-color: #b13535;
        }
        .custom-switch .custom-control-label::after {
            background-color: #5e35b1;
        }
        .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #4a148c;
        }
        .custom-control-label {
            cursor: pointer;
        }
        .table .custom-control-input {
            margin: auto;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="text-center mb-4">
        <i class="fas fa-hospital icon-clinic"></i>
        <h2>แก้ไขตารางทำการคลินิก</h2>
        <p class="text-muted">โรงพยาบาลเกษมราษฎร์ ประชาชื่น</p>
    </div>

    <div class="form-group">
        <label for="clinic_id"><i class="fas fa-clinic-medical"></i> เลือกคลินิก:</label>
        <select id="clinic_id" class="form-control">
            <option value="0"></option>
            <option value="1">ทันตกรรม</option>
            <option value="2">อายุรเวท</option>
            <!-- Add more clinic options as needed -->
        </select>
    </div>

    <div class="form-group">
        <label for="day_of_week"><i class="fas fa-calendar-alt"></i> เลือกวันทำการ:</label>
        <select id="day_of_week" class="form-control">
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select>
    </div>

    <!-- <button id="fetch_schedule" class="btn btn-primary btn-block"><i class="fas fa-search"></i> ค้นหาตาราง</button> -->

    <div class="form-group mt-3">
        <label for="status_day"><i class="fas fa-calendar-check"></i> สถานะวันทำการ:</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" id="status_day" class="custom-control-input">
            <label class="custom-control-label" for="status_day">เปิดวันนี้</label>
        </div>
    </div>

    <button id="add_time_slot" class="btn btn-secondary btn-block mt-3"><i class="fas fa-plus"></i> เพิ่มช่วงเวลาทำการ</button>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ช่วงเวลา</th>
                <th>จำนวนเปิดรับคนไข้</th>
                <th>ช่วงเวลาเริ่มต้น</th>
                <th>ช่วงเวลาสิ้นสุด</th>
                <th>เปิดช่วงเวลานี้</th>
                <th>แก้ไข</th>
            </tr>
        </thead>
        <tbody id="schedule_table"></tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Remove the button for searching schedule -->
<!-- <button id="fetch_schedule" class="btn btn-primary btn-block"><i class="fas fa-search"></i> ค้นหาตาราง</button> -->

<script>
$(document).ready(function () {
    // Function to fetch schedule based on selected clinic and day
    function fetchSchedule() {
        const clinic_id = $('#clinic_id').val();
        const day_of_week = $('#day_of_week').val();

        $.get('fetch_schedule.php', { clinic_id: clinic_id, day_of_week: day_of_week }, function (data) {
            let scheduleHtml = '';

            // Assume `data` has a field called `status_day` with values 'ON' or 'OFF'
            const dayStatus = data.status_day === 'ON';

            // Set the status_day checkbox based on the fetched value
            $('#status_day').prop('checked', dayStatus);

            // Generate table rows for time slots
            data.time_slots.forEach(function (slot) {
                scheduleHtml += `
                    <tr>
                        <td>${slot.start_time} - ${slot.end_time}</td>
                        <td><input type="number" class="form-control" value="${slot.booked_count}" data-time-slot-id="${slot.time_slot_id}"></td>
                        <td><input type="time" class="form-control" value="${slot.start_time}" data-time-slot-id="${slot.time_slot_id}" data-type="start"></td>
                        <td><input type="time" class="form-control" value="${slot.end_time}" data-time-slot-id="${slot.time_slot_id}" data-type="end"></td>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input slot-active" id="slot_active_${slot.time_slot_id}" data-time-slot-id="${slot.time_slot_id}" ${slot.active ? 'checked' : ''}>
                                <label class="custom-control-label" for="slot_active_${slot.time_slot_id}"></label>
                            </div>
                        </td>
                        <td><button class="btn btn-success save-btn" data-time-slot-id="${slot.time_slot_id}"><i class="fas fa-save"></i> บันทึก</button></td>
                    </tr>
                `;
            });

            $('#schedule_table').html(scheduleHtml);
        }, 'json');
    }

    // Fetch schedule when clinic or day is changed
    $('#clinic_id, #day_of_week').change(function () {
        fetchSchedule(); // Call the fetch schedule function
    });

    // Add new empty time slot row
    $('#add_time_slot').click(function () {
        let newRow = `
            <tr>
                <td>New Time Slot</td>
                <td><input type="number" class="form-control" value="0"></td>
                <td><input type="time" class="form-control"></td>
                <td><input type="time" class="form-control"></td>
                <td>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" checked>
                        <label class="custom-control-label"></label>
                    </div>
                </td>
                <td><button class="btn btn-primary save-new-btn"><i class="fas fa-plus"></i> เพิ่มช่วงเวลา</button></td>
            </tr>
        `;
        $('#schedule_table').append(newRow);
    });
    $(document).on('click', '.save-new-btn', function () {
    let row = $(this).closest('tr');
    let slotCount = row.find('.slot-count').val();
    let startTime = row.find('.start-time').val();
    let endTime = row.find('.end-time').val();
    let isActive = row.find('.is-active').prop('checked') ? 1 : 0;



    $(document).on('click', '.save-new-btn', function () {
    const booked_count = $(this).closest('tr').find('input[type="number"]').val();
    const start_time = $(this).closest('tr').find('input[type="time"]').eq(0).val();
    const end_time = $(this).closest('tr').find('input[type="time"]').eq(1).val();
    const clinic_id = $('#clinic_id').val();
    const day_of_week = $('#day_of_week').val();

    $.post('add_time_slot.php', {
        clinic_id: clinic_id,
        day_of_week: day_of_week,
        booked_count: booked_count,
        start_time: start_time,
        end_time: end_time
    }, function (response) {
        // Check the response status
        if (response.status === "success") {
            Swal.fire('สำเร็จ', 'เพิ่มช่วงเวลาสำเร็จ!', 'success');
            fetchSchedule(); // Call fetchSchedule() to refresh data
        } else {
            Swal.fire('เกิดข้อผิดพลาด', response.error || 'ไม่สามารถเพิ่มช่วงเวลาได้!', 'error');
        }
    }, 'json').fail(function () {
        Swal.fire('บันทึกช่วงเวลาที่ไม่มีค่าเวลา', 'กรุณากรอกเวลาด้วยค่ะ!', 'error');
    });
});


});
    // Save updated time slot data including activation status
    $(document).on('click', '.save-btn', function () {
        saveSlotData($(this).data('time-slot-id'));
    });

    // Auto-save when switching active state
    $(document).on('change', '.slot-active', function () {
        saveSlotData($(this).data('time-slot-id'));
    });

    // Function to save time slot data
    function saveSlotData(time_slot_id) {
        const booked_count = $(`input[data-time-slot-id="${time_slot_id}"]`).val();
        const start_time = $(`input[data-time-slot-id="${time_slot_id}"][data-type="start"]`).val();
        const end_time = $(`input[data-time-slot-id="${time_slot_id}"][data-type="end"]`).val();
        const active = $(`#slot_active_${time_slot_id}`).is(':checked') ? 1 : 0;
        const clinic_id = $('#clinic_id').val();
        const day_of_week = $('#day_of_week').val();
        const day_active = $('#status_day').is(':checked') ? 1 : 0; // Get day status

        $.post('update_schedule.php', {
            time_slot_id: time_slot_id,
            booked_count: booked_count,
            start_time: start_time,
            end_time: end_time,
            active: active,
            clinic_id: clinic_id,
            day_of_week: day_of_week,
            day_active: day_active // Include day status in the request
        }, function (response) {
            alert('Data saved successfully!');
        });
    }

    // Auto-save when toggling the day active state
    $('#status_day').change(function () {
        const status_day = $(this).is(':checked') ? 'ON' : 'OFF'; // Change to 'ON' or 'OFF'
        const clinic_id = $('#clinic_id').val();
        const day_of_week = $('#day_of_week').val();

        $.post('update_schedule.php', {
            clinic_id: clinic_id,
            day_of_week: day_of_week,
            status_day: status_day // Use status_day instead of day_active
        }, function (response) {
            alert('Day status updated successfully!');
        });
    });
    // Save new time slot
    // Save new time slot



});
</script>


</body>
</html>
