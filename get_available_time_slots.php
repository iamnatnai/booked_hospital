<?php
header('Content-Type: application/json');

// Include the config file for database connection
require 'config.php'; // Ensure this file contains your database connection setup

// Get clinic_id and day_of_week from query parameters
$clinic_id = isset($_GET['clinic_id']) ? (int)$_GET['clinic_id'] : 0;
$day_of_week = isset($_GET['day_of_week']) ? $_GET['day_of_week'] : '';

if ($clinic_id <= 0 || empty($day_of_week)) {
    echo json_encode(['error' => 'Invalid clinic_id or day_of_week']);
    exit;
}

try {
    // Query to get time slots for the specified clinic and day_of_week
    $sql = "SELECT ts.id, ts.start_time, ts.end_time, ts.active, ts.booked_capacity, ts.is_delete
            FROM time_slots ts
            JOIN clinic_schedule cs ON ts.id_clinic_schedule = cs.id
            WHERE cs.clinic_id = :clinic_id AND cs.day_of_week = :day_of_week AND ts.active = 1 AND ts.is_delete = 0";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['clinic_id' => $clinic_id, 'day_of_week' => $day_of_week]);
    $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any slots were found
    if ($slots) {
        // Output the combined data
        echo json_encode($slots);
    } else {
        echo json_encode([]); // No data found
    }

} catch (PDOException $e) {
    // Log error details for debugging
    error_log("Received clinic_id: " . $_GET['clinic_id']);
error_log("Received day_of_week: " . $_GET['day_of_week']);
    error_log($e->getMessage()); // Ensure appropriate error logging
    echo json_encode(['error' => 'An error occurred while processing your request.']);
}

?>
