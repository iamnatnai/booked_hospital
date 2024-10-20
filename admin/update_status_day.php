<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to your database
require 'config.php';

// Check if the required POST parameters are set
if (isset($_POST['clinic_id'], $_POST['day_of_week'], $_POST['status'])) {
    $clinic_id = intval($_POST['clinic_id']);
    $day_of_week = $_POST['day_of_week']; // e.g., 'Monday', 'Tuesday', etc.
    $status = intval($_POST['status']); // 1 = open, 0 = closed

    // Prepare the SQL statement to update the day status in the clinic_schedule table
    $stmt = $conn->prepare("UPDATE clinic_schedule SET status = ? WHERE clinic_id = ? AND day_of_week = ?");
    $stmt->bind_param("iis", $status, $clinic_id, $day_of_week);

    if ($stmt->execute()) {
        echo "Day status updated successfully.";
    } else {
        echo "Failed to update day status.";
    }

    $stmt->close();
} else {
    echo "Invalid input.";
}

// Close the database connection
$conn->close();
