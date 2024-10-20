<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambulance Booking</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/blue-stack/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            color: #555;
        }
        input, select, button {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
        }
        button.blue-btn {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button.blue-btn:hover {
            background-color: #0056b3;
        }
        .info-message {
            margin-top: 15px;
            color: #e74c3c;
        }
        #calendar {
            max-width: 900px;
            margin: 0 auto;
            margin-top: 20px;
        }
        .ambulance-selection {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .ambulance-option {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            width: 200px;
            cursor: pointer;
            background-color: #f9f9f9;
        }
        .ambulance-option img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .ambulance-details {
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ambulance Booking System</h1>
        <form action="booking.php" method="POST">
            <label for="booking_type">ประสงค์จะเลือกรถพยาบาลเองไหม:</label>
            <select id="booking_type" name="booking_type" onchange="toggleBookingOptions()">
                <option value="assign" selected>ให้ทางรถพยาบาลเลือกให้</option>
                <option value="choose">เลือกรถเอง</option>
            </select>

            <!-- Car Selection -->
            <div id="car_selection" style="display: none;">
                <label>เลือกรถพยาบาล:</label>
                <div class="ambulance-selection">
                    <div class="ambulance-option">
                        <input type="radio" id="ambulance1" name="ambulance_id" value="1" required>
                        <label for="ambulance1">
                            <img src="path-to-ambulance-image1.jpg" alt="Ambulance 1">
                            <div class="ambulance-details">
                                <p>ทะเบียน: กข 1234</p>
                                <p>คนขับ: นาย A</p>
                            </div>
                        </label>
                    </div>
                    <div class="ambulance-option">
                        <input type="radio" id="ambulance2" name="ambulance_id" value="2" required>
                        <label for="ambulance2">
                            <img src="path-to-ambulance-image2.jpg" alt="Ambulance 2">
                            <div class="ambulance-details">
                                <p>ทะเบียน: ขค 5678</p>
                                <p>คนขับ: นาย B</p>
                            </div>
                        </label>
                    </div>
                    <!-- Add more ambulance options as needed -->
                </div>
            </div>

            <!-- Booking Dates -->
            <div id="booking_dates">
                <label for="booking_duration">ประเภทการจอง:</label>
                <select id="booking_duration" name="booking_duration" onchange="toggleBookingFields()">
                    <option value="single">1 วัน</option>
                    <option value="multiple">หลายวัน</option>
                </select>

                <!-- Single Day Fields -->
                <div id="single_day_fields" style="display: block;">
                    <label for="date">เลือกวันที่:</label>
                    <input type="date" id="date" name="date">
                    
                    <label for="time_slot">เลือกช่วงวัน:</label>
                    <select id="time_slot" name="time_slot">
                        <option value="morning">เช้า-เที่ยง</option>
                        <option value="afternoon">บ่าย-เย็น</option>
                        <option value="evening">ค่ำ</option>
                    </select>
                </div>

                <!-- Multiple Days Fields -->
                <div id="multi_day_fields" style="display: none;">
                    <label for="start_date">วันที่เริ่มต้น:</label>
                    <input type="date" id="start_date" name="start_date">
                    
                    <label for="end_date">วันที่สิ้นสุด:</label>
                    <input type="date" id="end_date" name="end_date">
                </div>
            </div>

            <label for="user_id">Your User ID:</label>
            <input type="number" id="user_id" name="user_id" required>
            
            <button type="submit" class="blue-btn">Book Now</button>

            <div id="info_message" class="info-message"></div>
        </form>
    </div>

    <div id="calendar"></div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: 'fetch_events.php',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                eventColor: '#007bff'
            });
            calendar.render();
        });

        function toggleBookingOptions() {
            var bookingType = document.getElementById("booking_type").value;
            var carSelection = document.getElementById("car_selection");
            var infoMessage = document.getElementById("info_message");

            if (bookingType === "choose") {
                carSelection.style.display = "block";
                infoMessage.textContent = "";
            } else {
                carSelection.style.display = "none";
                infoMessage.textContent = "คำขอจองของท่านจะถูกส่งให้ทางรถพยาบาลเลือกอีกที";
            }
        }

        function toggleBookingFields() {
            var bookingDuration = document.getElementById("booking_duration").value;
            var singleDayFields = document.getElementById("single_day_fields");
            var multiDayFields = document.getElementById("multi_day_fields");

            if (bookingDuration === "single") {
                singleDayFields.style.display = "block";
                multiDayFields.style.display = "none";
            } else if (bookingDuration === "multiple") {
                singleDayFields.style.display = "none";
                multiDayFields.style.display = "block";
            }
        }

        // Call toggleBookingOptions to set initial display state
        toggleBookingOptions();
        toggleBookingFields();
    </script>
</body>
</html>
