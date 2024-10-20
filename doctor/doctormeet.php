<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตั้งค่าตารางเวลาการทำงานของคลินิก</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            margin-top: 30px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h3 {
            color: #6a1b9a;
            margin-bottom: 20px;
        }
        .time-slot-label {
            margin-right: 10px;
        }
        .btn-submit {
            background-color: #6a1b9a;
            color: white;
        }
        .btn-submit:hover {
            background-color: #8e24aa;
        }
        .time-slots {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>ตั้งค่าตารางเวลาการทำงานของคลินิก</h3>
    <form id="schedule-form" action="save_schedule.php" method="POST">
        <input type="hidden" name="clinic_id" value="1">

        <!-- Select Days -->
        <div class="form-group">
            <label for="days" class="font-weight-bold">เลือกวันที่เข้ามาทำงาน:</label><br>
            <label class="time-slot-label"><input type="checkbox" class="day-checkbox" name="days[Monday]" value="Monday"> วันจันทร์</label>
            <label class="time-slot-label"><input type="checkbox" class="day-checkbox" name="days[Tuesday]" value="Tuesday"> วันอังคาร</label>
            <label class="time-slot-label"><input type="checkbox" class="day-checkbox" name="days[Wednesday]" value="Wednesday"> วันพุธ</label>
            <label class="time-slot-label"><input type="checkbox" class="day-checkbox" name="days[Thursday]" value="Thursday"> วันพฤหัสบดี</label>
            <label class="time-slot-label"><input type="checkbox" class="day-checkbox" name="days[Friday]" value="Friday"> วันศุกร์</label>
            <label class="time-slot-label"><input type="checkbox" class="day-checkbox" name="days[Saturday]" value="Saturday"> วันเสาร์</label>
            <label class="time-slot-label"><input type="checkbox" class="day-checkbox" name="days[Sunday]" value="Sunday"> วันอาทิตย์</label>
        </div>

        <!-- Time Slots (Initially hidden) -->
        <div id="time-slots" class="form-group time-slots">
            <h4 class="font-weight-bold">เลือกช่วงเวลา:</h4>
            <div id="time-slot-options"></div>
        </div>

        <button type="submit" class="btn btn-submit btn-block mt-4">บันทึกตารางเวลา</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        const timeSlots = {
            '1': '09:00 - 10:00',
            '2': '10:00 - 11:00',
            '3': '11:00 - 12:00',
            '4': '12:00 - 13:00',
            '5': '13:00 - 14:00'
        };

        $('.day-checkbox').change(function() {
            const selectedDays = $('.day-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedDays.length > 0) {
                $('#time-slots').show();
                $('#time-slot-options').empty();

                selectedDays.forEach(day => {
                    $('#time-slot-options').append(`
                        <div class="form-group">
                            <label class="font-weight-bold">${day}:</label><br>
                            ${Object.keys(timeSlots).map(slot => `
                                <label class="time-slot-label">
                                    <input type="checkbox" name="schedule[${day}][]" value="${slot}"> ${timeSlots[slot]}
                                </label>
                            `).join('')}
                        </div>
                    `);
                });
            } else {
                $('#time-slots').hide();
            }
        });
    });
</script>

</body>
</html>
