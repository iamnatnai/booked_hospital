<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครผู้ป่วยใหม่ - คลินิกทันตกรรม</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f3e6f8; /* สีพื้นหลังเบา ๆ */
        }
        .container {
            background-color: #6a1b9a; /* สีม่วงเข้ม */
            color: white;
            padding: 30px;
            border-radius: 10px;
        }
        .form-group label {
            font-size: 1.2rem; /* ขนาดตัวอักษรของ label */
        }
        .form-control {
            border-radius: 5px;
            font-size: 1rem; /* ขนาดตัวอักษรของ input fields */
        }
        .btn-primary {
            background-color: #8e24aa; /* สีม่วงเข้ม */
            border: none;
            font-size: 1.1rem; /* ขนาดตัวอักษรของปุ่ม */
        }
        .btn-primary:hover {
            background-color: #7b1fa2; /* สีม่วงเข้มกว่าหมายถึงสถานะ Hover */
        }
        .date-display {
            font-size: 1rem; /* ขนาดตัวอักษรของการแสดงวันที่ */
            color: #d1c4e9; /* สีอักษรของการแสดงวันที่ */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4" style="font-size: 2rem;">สมัครผู้ป่วยใหม่</h2>
        <form id="registrationForm">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="national_id">เลขบัตรประชาชน:</label>
                    <input type="text" class="form-control" id="national_id" name="national_id" required maxlength="17" placeholder="0-0000-00000-00-0">
                </div>
               
                <div class="form-group col-md-6">
                    <label for="birth_date">วัน/เดือน/ปี เกิด:</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                    <small class="form-text text-muted">วัน/เดือน/ปี เกิดจะถูกแสดงในรูปแบบไทย</small>
                    <p id="formatted_date_display" class="date-display"></p>
                    <input type="hidden" id="formatted_birth_date" name="formatted_birth_date">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="first_name">ชื่อ:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="last_name">นามสกุล:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="phone_number">เบอร์โทรศัพท์:</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>ใช้ยาละลายลิ่มเลือด:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="blood_thinner" id="blood_thinner_yes" value="yes" required>
                        <label class="form-check-label" for="blood_thinner_yes">ใช่</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="blood_thinner" id="blood_thinner_no" value="no" required>
                        <label class="form-check-label" for="blood_thinner_no">ไม่ใช่</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>โรคประจำตัว:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="medical_condition[]" id="diabetes" value="diabetes">
                        <label class="form-check-label" for="diabetes">เบาหวาน</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="medical_condition[]" id="hypertension" value="hypertension">
                        <label class="form-check-label" for="hypertension">ความดัน</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="medical_condition[]" id="heart_disease" value="heart_disease">
                        <label class="form-check-label" for="heart_disease">โรคหัวใจ</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="medical_condition[]" id="lung_disease" value="lung_disease">
                        <label class="form-check-label" for="lung_disease">โรคปอด</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="medical_condition[]" id="other_condition" value="other_condition">
                        <label class="form-check-label" for="other_condition">อื่น ๆ</label>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="submitBtn">สมัครผู้ป่วย</button>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">ข้อมูลใบสมัคร</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>เลขบัตรประชาชน:</strong> <span id="modal_national_id"></span></p>
                    <p><strong>วัน/เดือน/ปี เกิด:</strong> <span id="modal_birth_date"></span></p>
                    <p><strong>ชื่อ:</strong> <span id="modal_first_name"></span></p>
                    <p><strong>นามสกุล:</strong> <span id="modal_last_name"></span></p>
                    <p><strong>เบอร์โทรศัพท์:</strong> <span id="modal_phone_number"></span></p>
                    <p><strong>ใช้ยาละลายลิ่มเลือด:</strong> <span id="modal_blood_thinner"></span></p>
                    <p><strong>โรคประจำตัว:</strong> <span id="modal_medical_condition"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary" id="confirmBtn">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Apply mask to the national_id input
            $('#national_id').mask('0-0000-00000-00-0', {placeholder: "0-0000-00000-00-0"});
            
            // Apply mask to the phone_number input
            $('#phone_number').mask('000-000-0000', {placeholder: "000-000-0000"});

            function formatThaiDate(date) {
                const months = [
                    "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                    "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
                ];
                const d = new Date(date);
                const day = d.getDate();
                const month = months[d.getMonth()];
                const year = d.getFullYear() + 543; // เพิ่ม 543 ปีสำหรับปฏิทินไทย
                return `${day} ${month} ${year}`;
            }

            $('#birth_date').on('change', function() {
                const selectedDate = this.value;
                const formattedDate = formatThaiDate(selectedDate);
                $('#formatted_date_display').text(formattedDate); // แสดงวันที่ในรูปแบบไทย
                $('#formatted_birth_date').val(selectedDate); // เก็บวันที่ในรูปแบบ ISO
            });

            $('#submitBtn').on('click', function() {
                // Validate form inputs
                const national_id = $('#national_id').val();
                const birth_date = $('#birth_date').val();
                const first_name = $('#first_name').val();
                const last_name = $('#last_name').val();
                const phone_number = $('#phone_number').val();
                const blood_thinner = $('input[name="blood_thinner"]:checked').val();
                const medical_condition = $('input[name="medical_condition[]"]:checked').length;

                if (!national_id || !birth_date || !first_name || !last_name || !phone_number || !blood_thinner || medical_condition === 0) {
                    alert('กรุณากรอกข้อมูลให้ครบถ้วน');
                    return;
                }

                // Set values in modal
                $('#modal_national_id').text(national_id);
                $('#modal_birth_date').text($('#formatted_date_display').text());
                $('#modal_first_name').text(first_name);
                $('#modal_last_name').text(last_name);
                $('#modal_phone_number').text(phone_number);
                $('#modal_blood_thinner').text($('input[name="blood_thinner"]:checked').next('label').text());
                $('#modal_medical_condition').text($('input[name="medical_condition[]"]:checked').map(function() {
                    return $(this).next('label').text();
                }).get().join(', '));

                // Show modal
                $('#confirmationModal').modal('show');
            });

            $('#confirmBtn').on('click', function() {
                $('#registrationForm').submit(); // Submit the form after confirmation
            });
        });
    </script>
</body>
</html>
