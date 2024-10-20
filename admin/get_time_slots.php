<?php
include 'conection.php';
$id_clinic_schedule = $_POST['id_clinic_schedule'];
$query = "SELECT * FROM time_slot WHERE id_clinic_schedule = '$id_clinic_schedule' AND is_delete = 0";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    echo "<p>เริ่ม: {$row['start_time']} - สิ้นสุด: {$row['end_time']} - จองได้: {$row['booked_capacity']}</p>";
}
?>
