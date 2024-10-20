<?php
$host = 'localhost';
$db = 'kasemra2_book';
$user = 'kasemra2_book';
$pass = 'kasemrad@64';

// Database connection
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clinic_id = $_POST['clinic_id'];
    $day_of_week = $_POST['day_of_week'];
    $time_slot_id = $_POST['time_slot_id'];
    $new_booked_count = $_POST['booked_count'];
    $new_start_time = $_POST['start_time'];
    $new_end_time = $_POST['end_time'];
    $active = isset($_POST['active']) ? (int)$_POST['active'] : 0;

    // Retrieve the current status_day before updating
    $sql = "SELECT status_day FROM clinic_schedule WHERE clinic_id = ? AND day_of_week = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $clinic_id, $day_of_week);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_status_day = 'ON'; // Default value
    if ($row = $result->fetch_assoc()) {
        $current_status_day = $row['status_day'];
    }

    // Handle status_day input from the form, default to 'ON' if not set
    $status_day = isset($_POST['status_day']) ? strtoupper($_POST['status_day']) : 'ON';

    // Only allow 'ON' or 'OFF' as valid status_day values
    if ($status_day !== 'ON' && $status_day !== 'OFF') {
        $status_day = $current_status_day; // Retain the current status if invalid input
    }

    // Update start_time, end_time, booked_count, and active status in time_slots
    $time_update_sql = "UPDATE time_slots SET start_time = ?, end_time = ?, booked_count = ?, active = ? WHERE id = ?";
    $time_update_stmt = $conn->prepare($time_update_sql);
    $time_update_stmt->bind_param('ssiii', $new_start_time, $new_end_time, $new_booked_count, $active, $time_slot_id);

    if (!$time_update_stmt->execute()) {
        echo "Error updating time_slots: " . $time_update_stmt->error;
        exit();
    }

    // Optionally, update the clinic_schedule status_day if needed
    $update_schedule_sql = "UPDATE clinic_schedule SET status_day = ? WHERE clinic_id = ? AND day_of_week = ?";
    $update_schedule_stmt = $conn->prepare($update_schedule_sql);
    $update_schedule_stmt->bind_param('sis', $status_day, $clinic_id, $day_of_week);
    if (!$update_schedule_stmt->execute()) {
        echo "Error updating clinic_schedule: " . $update_schedule_stmt->error;
        exit();
    }

    echo "Update successful";
}
?>
