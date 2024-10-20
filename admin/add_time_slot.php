<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
$host = 'localhost';
$db = 'kasemra2_book';
$user = 'kasemra2_book';
$pass = 'kasemrad@64';

// Database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "error" => "Connection failed: " . $conn->connect_error]));
}

// Get the data from the POST request and validate
$clinic_id = isset($_POST['clinic_id']) ? intval($_POST['clinic_id']) : null;
$day_of_week = isset($_POST['day_of_week']) ? $_POST['day_of_week'] : null;
$start_time = isset($_POST['start_time']) ? $_POST['start_time'] : null;
$end_time = isset($_POST['end_time']) ? $_POST['end_time'] : null;
$active = isset($_POST['active']) ? intval($_POST['active']) : 1; // Default to active
$booked_capacity = isset($_POST['booked_capacity']) ? intval($_POST['booked_capacity']) : 0; // Default to 0
$is_delete = isset($_POST['is_delete']) ? intval($_POST['is_delete']) : 0; // Default to 0 (not deleted)

// Prepare response array
$response = ["status" => "success"];

// Check if the required parameters are provided
if ($clinic_id === null || $day_of_week === null || $start_time === null || $end_time === null) {
    die(json_encode(['status' => 'error', 'error' => 'Missing required parameters.']));
}

// Step 1: Retrieve the clinic_schedule id for the given clinic and day_of_week
$sql = "SELECT id FROM clinic_schedule WHERE clinic_id = ? AND day_of_week = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $clinic_id, $day_of_week);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $id_clinic_schedule = $row['id']; // Get the id_clinic_schedule
} else {
    die(json_encode(['status' => 'error', 'error' => 'No clinic schedule found for the specified clinic and day.']));
}

// Step 2: Insert the new time slot into the `time_slots` table
$insert_time_slot_sql = "INSERT INTO time_slots (start_time, end_time, active, id_clinic_schedule, booked_capacity, is_delete) VALUES (?, ?, ?, ?, ?, ?)";
$stmt2 = $conn->prepare($insert_time_slot_sql);

if (!$stmt2) {
    die(json_encode(['status' => 'error', 'error' => 'Failed to prepare statement: ' . $conn->error]));
}

$stmt2->bind_param('ssiiii', $start_time, $end_time, $active, $id_clinic_schedule, $booked_capacity, $is_delete);

if ($stmt2->execute()) {
    // Optionally, you can return the ID of the newly inserted time slot
    $time_slot_id = $stmt2->insert_id;

    $response["time_slot_id"] = $time_slot_id; // Include the new ID in the response
} else {
    $response["status"] = "error";
    $response["error"] = "Error inserting new time slot: " . $stmt2->error;
}

$stmt2->close(); // Close the time slot statement
$conn->close(); // Close the database connection

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
