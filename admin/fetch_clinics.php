<?php
// fetch_clinics.php
include 'conection.php'; // Include your database connection

$query = "SELECT id, name, th_clinicname FROM clinics";
$result = mysqli_query($conn, $query);

$clinics = [];
while ($row = mysqli_fetch_assoc($result)) {
    $clinics[] = $row;
}

echo json_encode($clinics);
?>
