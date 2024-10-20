<?php
include 'conection.php'; // Ensure you have the correct database connection

// Check if the necessary data is sent via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = $_POST['id']; // Event ID to update
    $newStatus = $_POST['status']; // New status ('accept' or 'waiting')

    // Prepare the SQL statement to update the event status
    $sql = "UPDATE booked_confirm SET status = ? WHERE booked_id = ?";
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared successfully
    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("si", $newStatus, $eventId);
        if ($stmt->execute()) {
            // Successfully updated
            echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
        } else {
            // Error during execution
            echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
        }
        $stmt->close();
    } else {
        // SQL statement preparation failed
        echo json_encode(['success' => false, 'message' => 'SQL error.']);
    }
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$conn->close(); // Close the database connection
?>
