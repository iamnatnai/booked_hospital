<?php
// get_time_slots.php
header('Content-Type: application/json');

// Replace with your database connection
$pdo = new PDO('mysql:host=localhost;dbname=your_db', 'username', 'password');

// Fetch time slots
$sql = 'SELECT * FROM timeslot';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$timeSlots = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($timeSlots);
?>
