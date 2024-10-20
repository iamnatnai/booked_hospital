<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Clinic Schedule - โรงพยาบาลเกษมราษฎร์ ประชาชื่น</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="settimestyles.css"> <!-- Link to the new CSS file -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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
            <option value="0">เลือกคลินิก</option>
            <!-- Clinic options will be populated here -->
        </select>
    </div>
    
    <div class="form-group">
        <button id="add_clinic" class="btn btn-primary mt-2">เพิ่มคลินิก</button>
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
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody id="schedule_table"></tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function () {
    // Function to fetch clinics and populate the dropdown
    function fetchClinics() {
        $.get('fetch_clinics.php', function (data) {
            const clinics = JSON.parse(data);
            clinics.forEach(function (clinic) {
                $('#clinic_id').append(
                    `<option value="${clinic.id}">${clinic.th_clinicname} (${clinic.name})</option>`
                );
            });
        });
    }

    // Function to fetch the schedule based on selected clinic and day
    function fetchSchedule() {
        const clinic_id = $('#clinic_id').val();
        const day_of_week = $('#day_of_week').val();

        $.get('fetch_schedule.php', { clinic_id: clinic_id, day_of_week: day_of_week }, function (data) {
            let scheduleHtml = '';

            const dayStatus = data.status_day === 'ON';
            $('#status_day').prop('checked', dayStatus);

            data.time_slots.forEach(function (slot) {
                scheduleHtml += `
                    <tr>
                        <td>${slot.start_time} - ${slot.end_time}</td>
                        <td><span class="form-control">${slot.booked_capacity}</span></td>
                        <td><span class="form-control">${slot.start_time}</span></td>
                        <td><span class="form-control">${slot.end_time}</span></td>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input slot-active" id="slot_active_${slot.time_slot_id}" data-time-slot-id="${slot.time_slot_id}" ${slot.active ? 'checked' : ''}>
                                <label class="custom-control-label" for="slot_active_${slot.time_slot_id}"></label>
                            </div>
                        </td>
                        <td><button class='btn btn-danger delete_time_slot' data-id='${slot.time_slot_id}'>
                        <i class="fas fa-trash-alt"></i> ลบ
                        </button>
                        </td>
                    </tr>
                `;
            });

            $('#schedule_table').html(scheduleHtml);
        }, 'json');
    }

    // Fetch clinics when the document is ready
    fetchClinics();

    // Event listeners for dropdown changes
    $('#clinic_id, #day_of_week').change(function () {
        fetchSchedule();
    });

    $('#add_time_slot').click(function () {
        let newRow = `
            <tr>
                <td>New Time Slot</td>
                <td><input type="number" class="form-control" value="0" required></td>
                <td><input type="time" class="form-control" required></td>
                <td><input type="time" class="form-control" required></td>
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
        const row = $(this).closest('tr');
        const booked_capacity = row.find('input[type="number"]').val();
        const start_time = row.find('input[type="time"]').eq(0).val();
        const end_time = row.find('input[type="time"]').eq(1).val();
        const clinic_id = $('#clinic_id').val();
        const day_of_week = $('#day_of_week').val();

        // Basic validation
        if (!booked_capacity || !start_time || !end_time) {
            Swal.fire('ข้อผิดพลาด', 'กรุณากรอกข้อมูลให้ครบถ้วน', 'error');
            return;
        }

        $.post('add_time_slot.php', { clinic_id, day_of_week, booked_capacity, start_time, end_time }, function (response) {
            // ตรวจสอบสถานะการตอบสนอง
            if (response.status === 'success') {
                // ถ้าบันทึกสำเร็จ
                Swal.fire('สำเร็จ', 'บันทึกข้อมูลเรียบร้อยแล้ว, ID ช่วงเวลา: ' + response.time_slot_id, 'success');
                fetchSchedule(); // Refresh the schedule
            } else {
                // ถ้ามีข้อผิดพลาด
                Swal.fire('ข้อผิดพลาด', response.error || 'ไม่ทราบข้อผิดพลาด', 'error');
            }
        }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
            // จัดการข้อผิดพลาดเมื่อมีปัญหากับ AJAX request
            Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการติดต่อเซิร์ฟเวอร์: ' + textStatus, 'error');
        });
    });



    $(document).on('click', '.delete_time_slot', function () {
        const time_slot_id = $(this).data('id');
        const clinic_id = $('#clinic_id').val();
        const day_of_week = $('#day_of_week').val();

        $.post('delete_time_slot.php', { time_slot_id, clinic_id, day_of_week }, function (response) {
            if (response.success) {
                Swal.fire('สำเร็จ', response.message, 'success');
                fetchSchedule();
            } else {
                Swal.fire('ข้อผิดพลาด', response.message, 'error');
            }
        }, 'json');
    });

    $('#add_clinic').click(function () {
        Swal.fire({
            title: 'เพิ่มคลินิก',
            html: `
                <div style="display: flex; flex-direction: column;">
                    <label for="new_clinic_th_name">ชื่อคลินิก (ไทย):</label>
                    <input type="text" id="new_clinic_th_name" class="swal2-input" placeholder="ชื่อคลินิก (ไทย)" required>
                    <label for="new_clinic_name">ชื่อคลินิก:</label>
                    <input type="text" id="new_clinic_name" class="swal2-input" placeholder="ชื่อคลินิก" required>
                </div>
            `,
            focusConfirm: false,
            preConfirm: () => {
                const newClinicThName = document.getElementById('new_clinic_th_name').value;
                const newClinicName = document.getElementById('new_clinic_name').value;

                if (!newClinicThName || !newClinicName) {
                    Swal.showValidationMessage('กรุณากรอกข้อมูลให้ครบถ้วน');
                }

                return { newClinicThName, newClinicName };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { newClinicThName, newClinicName } = result.value;
                $.post('add_clinic.php', { th_name: newClinicThName, name: newClinicName }, function (response) {
                    if (response.success) {
                        Swal.fire('สำเร็จ', response.message, 'success');
                        fetchClinics();
                    } else {
                        Swal.fire('ข้อผิดพลาด', response.message, 'error');
                    }
                }, 'json');
            }
        });
    });

     // Change the status of the day when the checkbox is toggled
     $('#status_day').change(function () {
        const clinic_id = $('#clinic_id').val();
        const day_of_week = $('#day_of_week').val();
        const isActive = $(this).is(':checked') ? 'ON' : 'OFF';

        $.post('update_day_status.php', { clinic_id, day_of_week, isActive }, function (response) {
            if (response.status === 'success') {
                Swal.fire('สำเร็จ', 'ปรับปรุงสถานะวันทำการเรียบร้อยแล้ว', 'success');
            } else {
                Swal.fire('ข้อผิดพลาด', response.error || 'ไม่ทราบข้อผิดพลาด', 'error');
            }
        }, 'json');
    });

    // Change the status of time slots when the checkbox is toggled
    $(document).on('change', '.slot-active', function () {
        const time_slot_id = $(this).data('time-slot-id');
        const isActive = $(this).is(':checked') ? '1' : '0';

        $.post('update_time_slot_status.php', { time_slot_id, isActive }, function (response) {
            if (response.status === 'success') {
                Swal.fire('สำเร็จ', 'ปรับปรุงสถานะช่วงเวลาเรียบร้อยแล้ว', 'success');
            } else {
                Swal.fire('ข้อผิดพลาด', response.error || 'ไม่ทราบข้อผิดพลาด', 'error');
            }
        }, 'json');
    });
});

</script>

</body>
</html>
