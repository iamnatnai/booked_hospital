<?php
// update_time_slot_status.php
include 'conection.php';

$time_slot_id = $_POST['time_slot_id'];
$isActive = $_POST['isActive'];

$query = "UPDATE time_slots SET active = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $isActive, $time_slot_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
