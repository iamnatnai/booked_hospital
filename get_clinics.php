<?php
header('Content-Type: application/json');

// Include the config file for database connection
require 'config.php'; // Ensure this file contains your database connection setup

// Fetch clinic data
$sql = "SELECT id, th_clinicname FROM clinics"; // Adjust table and column names as needed

try {
    $stmt = $pdo->query($sql);
    $clinics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($clinics);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
