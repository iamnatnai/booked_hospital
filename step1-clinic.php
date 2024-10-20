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
    <title>เลือกคลินิก - การจองคิวแพทย์</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background-color: #f0f4f7; }
        .container { background-color: #fff; color: #333; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .btn-primary { background-color: #6f42c1; border: none; font-size: 1.1rem; }
        .btn-primary:hover { background-color: #5a2e91; }
        .progress-bar { background-color: #6f42c1; height: 5px; transition: width 0.5s; }
        .progress-steps { margin-bottom: 20px; }
        .progress-step { width: 25%; float: left; text-align: center; font-size: 1rem; position: relative; }
        .progress-step.active { color: #6f42c1; }
        .progress-step.completed { color: #6f42c1; }
        .progress-step::before { content: ''; width: 24px; height: 24px; background-color: #fff; border: 2px solid #6f42c1; border-radius: 50%; display: inline-block; position: absolute; left: 50%; transform: translateX(-50%); top: -14px; z-index: 1; }
        .progress-step.completed::before { background-color: #6f42c1; border: none; }
        .progress-step.completed::after { content: '✓'; position: absolute; left: 50%; transform: translateX(-50%); top: -12px; color: #fff; font-size: 14px; }
        .step-content { display: none; }
        .step-content.active { display: block; }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2 class="mb-4 text-center" style="font-size: 2rem; color: #6f42c1;">การจองคิวแพทย์</h2>

        <div class="progress mb-4">
            <div id="progressBar" class="progress-bar"></div>
        </div>
        <div class="progress-steps">
            <div class="progress-step active" id="step1Indicator">1. เลือกคลินิก</div>
            <div class="progress-step" id="step2Indicator">2. เลือกวัน</div>
            <div class="progress-step" id="step3Indicator">3. เลือกช่วงเวลา</div>
            <div class="progress-step" id="step4Indicator">4. ยืนยัน</div>
        </div>

        <!-- Step 1: Clinic Selection -->
        <div id="step1" class="step-content active">
            <form id="clinicForm">
                <div class="form-group">
                    <label for="clinic">เลือกคลินิก:</label>
                    <select id="clinic" name="clinic" class="form-control" required>
                        <!-- Options will be dynamically added here -->
                    </select>
                </div>
                <button type="button" class="btn btn-primary" onclick="nextStep()">ถัดไป</button>
            </form>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch clinic data and populate the select options
            fetch('get_clinics.php')
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data)) {
                        const clinicSelect = $('#clinic');
                        data.forEach(clinic => {
                            clinicSelect.append(`<option value="${clinic.id}">${clinic.th_clinicname}</option>`);
                        });
                    } else {
                        console.error('Failed to load clinic data.');
                    }
                })
                .catch(error => console.error('Error fetching clinic data:', error));

            const currentStep = 1; // Step 1 for this page
            updateProgressBar(currentStep);
        });

        function nextStep() {
    const selectedClinic = $('#clinic').val();
    if (selectedClinic) {
        localStorage.setItem('selectedClinic', selectedClinic);
        updateProgressBar(2);
        window.location.href = 'step2-date.php'; // Go to the next page
    } else {
        alert('กรุณาเลือกคลินิก');
    }
}

        // Update progress bar and step completion status
        function updateProgressBar(step) {
            const progress = (step - 1) * 25; // Each step is 25%
            document.getElementById("progressBar").style.width = progress + "%";

            // Update step indicators
            for (let i = 1; i <= step; i++) {
                document.getElementById("step" + i + "Indicator").classList.add('completed');
            }
        }
    </script>
</body>
</html>
