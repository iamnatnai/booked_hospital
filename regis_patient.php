<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครผู้ป่วยใหม่ - คลินิกทันตกรรม</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
        <form id="registrationForm" action="register_patient.php" method="POST">
            <!-- Form fields here -->
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
            <!-- Additional fields here -->
            <button type="submit" class="btn btn-primary">สมัครผู้ป่วย</button>
        </form>
    </div>

    <!-- Modal for confirmation -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">รายละเอียดการสมัคร</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modal-content"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary" id="confirmSubmit">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#national_id').mask('0-0000-00000-00-0', {placeholder: "0-0000-00000-00-0"});
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
                $('#formatted_date_display').text(formattedDate);
                $('#formatted_birth_date').val(selectedDate);
            });

            $('#registrationForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                // Validate form fields
                let isValid = true;
                const fields = ['#national_id', '#birth_date', '#first_name', '#last_name', '#phone_number'];
                fields.forEach(field => {
                    if ($(field).val() === '') {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                        text: 'มีบางฟิลด์ที่คุณยังไม่ได้กรอก',
                    });
                    return;
                }

                // Show confirmation modal
                const modalContent = `
                    <strong>เลขบัตรประชาชน:</strong> ${$('#national_id').val()}<br>
                    <strong>วัน/เดือน/ปี เกิด:</strong> ${$('#formatted_date_display').text()}<br>
                    <strong>ชื่อ:</strong> ${$('#first_name').val()}<br>
                    <strong>นามสกุล:</strong> ${$('#last_name').val()}<br>
                    <strong>เบอร์โทรศัพท์:</strong> ${$('#phone_number').val()}<br>
                    <strong>ใช้ยาละลายลิ่มเลือด:</strong> ${$('input[name="blood_thinner"]:checked').next('label').text()}<br>
                    <strong>โรคประจำตัว:</strong> ${$('input[name="medical_condition[]"]:checked').map(function() { return $(this).next('label').text(); }).get().join(', ')}
                `;
                $('#modal-content').html(modalContent);
                $('#confirmationModal').modal('show');
            });

            $('#confirmSubmit').on('click', function() {
                $('#registrationForm').off('submit').submit(); // Unbind the submit event handler and submit the form
            });
        });
    </script>
</body>
</html>
