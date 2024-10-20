<?php
session_start();
?>
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
        .error-message {
            color: #d9534f; /* สีแดงสำหรับข้อความข้อผิดพลาด */
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .bg-purple {
    background-color: #6a1b9a;
}

.alert-info {
    background-color: #f3e6f8;
    color: #6a1b9a;
}

.list-group-item {
    background-color: #f9f9f9;
    font-size: 1rem;
    padding: 10px;
    margin-bottom: 5px;
}

.modal-content {
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4" style="font-size: 2rem;">สมัครผู้ป่วยใหม่</h2>

        <?php
        // แสดงข้อความข้อผิดพลาดถ้ามี
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']); // เคลียร์ข้อความข้อผิดพลาดหลังจากแสดง
        }
        ?>

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
    <button type="submit" class="btn btn-primary">สมัครผู้ป่วย</button>
</form>

    </div>

    <!-- Modal for confirmation -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- เปลี่ยนขนาด Modal -->
        <div class="modal-content">
            <div class="modal-header bg-purple text-white"> <!-- สีพื้นหลังและสีข้อความของ header -->
                <h5 class="modal-title" id="confirmationModalLabel">ยืนยันการสมัครผู้ป่วยใหม่</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> ข้อมูลที่คุณกรอกมีดังนี้:</h6>
                </div>
                <div id="modal-content" class="p-3 rounded bg-light"></div> <!-- เพิ่มการจัดวางและพื้นหลัง -->
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
                const year = d.getFullYear() + 543; // ปรับเป็นปีไทย
                return `${day} ${month} ${year}`;
            }

            $('#birth_date').change(function() {
                const formattedDate = formatThaiDate($(this).val());
                $('#formatted_birth_date').val($(this).val());
                $('#formatted_date_display').text(formattedDate);
            });

            $('#registrationForm').on('submit', function(e) {
    e.preventDefault();
    
    const nationalId = $('#national_id').val();
    const birthDate = $('#formatted_birth_date').val();
    const firstName = $('#first_name').val();
    const lastName = $('#last_name').val();
    const phoneNumber = $('#phone_number').val();
    const bloodThinner = $('input[name="blood_thinner"]:checked').val();
    const medicalConditions = $('input[name="medical_condition[]"]:checked')
                                .map(function() { return this.labels[0].innerText; })
                                .get()
                                .join(', ');

    const modalContent = `
        <ul class="list-group">
            <li class="list-group-item"><strong>เลขบัตรประชาชน:</strong> ${nationalId}</li>
            <li class="list-group-item"><strong>วัน/เดือน/ปี เกิด:</strong> ${birthDate}</li>
            <li class="list-group-item"><strong>ชื่อ:</strong> ${firstName}</li>
            <li class="list-group-item"><strong>นามสกุล:</strong> ${lastName}</li>
            <li class="list-group-item"><strong>เบอร์โทรศัพท์:</strong> ${phoneNumber}</li>
            <li class="list-group-item"><strong>ใช้ยาละลายลิ่มเลือด:</strong> ${bloodThinner}</li>
            <li class="list-group-item"><strong>โรคประจำตัว:</strong> ${medicalConditions}</li>
        </ul>
    `;

    $('#modal-content').html(modalContent); // แสดงรายการข้อมูลที่กรอกใน modal
    $('#confirmationModal').modal('show');
});


            $('#confirmSubmit').click(function() {
                $('#registrationForm')[0].submit();
            });
        });
    </script>
</body>
</html>
