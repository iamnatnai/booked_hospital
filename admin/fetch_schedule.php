<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host = 'localhost';
$db = 'kasemra2_book';
$user = 'kasemra2_book';
$pass = 'kasemrad@64';

// Establish database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Get clinic ID from the query parameters
$clinic_id = isset($_GET['clinic_id']) ? (int)$_GET['clinic_id'] : 1; // Default clinic_id is 1
$day_of_week = isset($_GET['day_of_week']) ? $_GET['day_of_week'] : 'Monday'; // Default day is Monday

// Validate day_of_week to prevent SQL Injection
$valid_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
if (!in_array($day_of_week, $valid_days)) {
    die(json_encode(['error' => 'Invalid day of the week']));
}

// Prepare SQL query to fetch clinic schedule based on clinic_id and day_of_week
$sql = "SELECT id, status_day FROM clinic_schedule WHERE clinic_id = ? AND day_of_week = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $clinic_id, $day_of_week);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
$status_day = 'OFF'; // Default status_day

if ($row = $result->fetch_assoc()) {
    $id_clinic_schedule = $row['id']; // Get the schedule ID
    $status_day = $row['status_day']; // Get status_day value

    // Fetch time slots linked to the clinic schedule
    $time_sql = "SELECT id, start_time, end_time, active, booked_capacity, is_delete 
                 FROM time_slots 
                 WHERE id_clinic_schedule = ? AND is_delete = 0"; // Include booked_capacity and filter out deleted slots
    $time_stmt = $conn->prepare($time_sql);
    $time_stmt->bind_param('i', $id_clinic_schedule);
    $time_stmt->execute();
    $time_result = $time_stmt->get_result();

    while ($time_row = $time_result->fetch_assoc()) {
        $data[] = [
            'time_slot_id' => $time_row['id'],
            'start_time' => $time_row['start_time'],
            'end_time' => $time_row['end_time'],
            'active' => $time_row['active'], // Include the active status
            'booked_capacity' => $time_row['booked_capacity'] // Include booked_capacity
        ];
    }
}

// Prepare the final output structure
$output = [
    'status_day' => $status_day,
    'time_slots' => $data
];

// Set the content type to application/json
header('Content-Type: application/json');

// Output the data as JSON
echo json_encode($output);

// Close the prepared statements and database connection
$stmt->close();
$conn->close();
?>
