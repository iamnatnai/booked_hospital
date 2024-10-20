<?php
// update_day_status.php
include 'conection.php';

$clinic_id = $_POST['clinic_id'];
$day_of_week = $_POST['day_of_week'];
$isActive = $_POST['isActive'];

// First, check if the record exists
$query_check = "SELECT * FROM clinic_schedule WHERE clinic_id = ? AND day_of_week = ?";
$stmt_check = $conn->prepare($query_check);
$stmt_check->bind_param("is", $clinic_id, $day_of_week);
$stmt_check->execute();
$result = $stmt_check->get_result();

// If the record exists, update it
if ($result->num_rows > 0) {
    $query_update = "UPDATE clinic_schedule SET status_day = ? WHERE clinic_id = ? AND day_of_week = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("sis", $isActive, $clinic_id, $day_of_week);
    
    if ($stmt_update->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Day updated']);
    } else {
        echo json_encode(['status' => 'error', 'error' => $stmt_update->error]);
    }

    $stmt_update->close();

// If the record does not exist, insert a new entry
} else {
    $query_insert = "INSERT INTO clinic_schedule (clinic_id, day_of_week, status_day) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("iss", $clinic_id, $day_of_week, $isActive);
    
    if ($stmt_insert->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'New day created']);
    } else {
        echo json_encode(['status' => 'error', 'error' => $stmt_insert->error]);
    }

    $stmt_insert->close();
}

$stmt_check->close();
$conn->close();
?>
