<?php
include 'connection.php'; // Ensure this path is correct

// Assuming you're getting clinic_id and day_of_week from GET parameters
$clinic_id = isset($_GET['clinic_id']) ? $_GET['clinic_id'] : null;
$day_of_week = isset($_GET['day_of_week']) ? $_GET['day_of_week'] : null;

// Check if parameters are not null
if ($clinic_id && $day_of_week) {
    $stmt = $conn->prepare("SELECT * FROM clinic_schedule WHERE clinic_id = ? AND day_of_week = ?");
    $stmt->bind_param("is", $clinic_id, $day_of_week); // Assuming clinic_id is an integer and day_of_week is a string
    $stmt->execute();
    $result = $stmt->get_result();

    // Assuming you want to check if the day is open
    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'open']);
    } else {
        echo json_encode(['status' => 'closed']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
}

$conn->close();
?>
