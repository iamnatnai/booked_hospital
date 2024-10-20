<?php
header('Content-Type: application/json');

// Include the config file for database connection
require 'config.php'; // Ensure this file contains your database connection setup

// Day of the week mapping
$daysOfWeek = [
    'Sunday'    => 0,
    'Monday'    => 1,
    'Tuesday'   => 2,
    'Wednesday' => 3,
    'Thursday'  => 4,
    'Friday'    => 5,
    'Saturday'  => 6
];

// Get the clinic_id and day_of_week from query parameters
$clinic_id = isset($_GET['clinic_id']) ? (int)$_GET['clinic_id'] : 0; // Default to 0 if not set
$day_of_week_text = isset($_GET['day_of_week']) ? $_GET['day_of_week'] : ''; // Default to empty string if not set

// Convert day_of_week from text to number
$day_of_week = isset($daysOfWeek[$day_of_week_text]) ? $daysOfWeek[$day_of_week_text] : -1;

if ($clinic_id <= 0 || $day_of_week < 0 || $day_of_week > 6) {
    echo json_encode(['error' => 'Invalid clinic_id or day_of_week']);
    exit;
}

try {
    // Query to get time_slot_data from clinic_schedule for the specified clinic and day_of_week
    $sql = "SELECT time_slot_data
            FROM clinic_schedule
            WHERE clinic_id = :clinic_id AND day_of_week = :day_of_week";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['clinic_id' => $clinic_id, 'day_of_week' => $day_of_week]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $timeSlotData = json_decode($result['time_slot_data'], true);

        if (empty($timeSlotData)) {
            echo json_encode([]);
            exit;
        }

        $timeSlotIds = array_column($timeSlotData, 'time_slot_id');

        if (empty($timeSlotIds)) {
            echo json_encode([]);
            exit;
        }

        // Prepare placeholders for the SQL IN clause
        $placeholders = rtrim(str_repeat('?,', count($timeSlotIds)), ',');

        // Query to get time slots from time_slots table
        $sqlSlots = "SELECT id, start_time, end_time
                     FROM time_slots
                     WHERE id IN ($placeholders)";
        $stmtSlots = $pdo->prepare($sqlSlots);
        $stmtSlots->execute($timeSlotIds);
        $slots = $stmtSlots->fetchAll(PDO::FETCH_ASSOC);

        // Combine the slot data with booking count
        $slotsById = [];
        foreach ($slots as $slot) {
            $slotsById[$slot['id']] = $slot;
        }

        foreach ($timeSlotData as $data) {
            if (isset($slotsById[$data['time_slot_id']])) {
                $slotsById[$data['time_slot_id']]['booked_count'] = $data['booked_count'];
            }
        }

        // Output the final combined data
        echo json_encode(array_values($slotsById));
    } else {
        echo json_encode([]);
    }
} catch (PDOException $e) {
    // Log error details for debugging purposes
    error_log($e->getMessage()); // Ensure you have appropriate error logging in place
    echo json_encode(['error' => 'An error occurred while processing your request.']);
}
?>
