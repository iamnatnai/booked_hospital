<?php
include 'conection.php';

// Check if the request is POST and required parameters are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['time_slot_id'], $_POST['clinic_id'], $_POST['day_of_week'])) {
    // Retrieve the parameters from the POST request
    $time_slot_id = $_POST['time_slot_id'];
    $clinic_id = $_POST['clinic_id'];
    $day_of_week = $_POST['day_of_week'];

    // Prepare the SQL statement to mark the time slot as deleted
    $query = "UPDATE time_slots SET is_delete = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $time_slot_id);

    // Execute the query and return the response
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Time slot deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting time slot.']);
    }

    // Close statement and connection
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$conn->close();
?>
