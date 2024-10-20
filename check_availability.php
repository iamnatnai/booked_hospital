<?php
// การเชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost', 'kasemra2_book', 'kasemrad@64', 'kasemra2_book');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$clinic_id = 1; // สมมติว่าคลินิกมี ID เป็น 1
$today = date('Y-m-d'); // หรือเปลี่ยนเป็นวันที่ที่ต้องการ

$sql = "SELECT date, time, capacity, booked_count
        FROM patient_time_slots
        WHERE date >= ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $today);
$stmt->execute();
$result = $stmt->get_result();

$availability = array();
while ($row = $result->fetch_assoc()) {
    $availability[] = $row;
}

echo json_encode($availability);

$stmt->close();
$conn->close();
?>
