<?php
// add_clinic.php
include 'conection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $th_clinicname = $_POST['th_name']; // Make sure this matches the POST field name

    // Use prepared statements to avoid SQL injection
    $query = "INSERT INTO clinics (name, th_clinicname) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $name, $th_clinicname); // Bind the variables to the query

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'คลินิกถูกเพิ่มเรียบร้อยแล้ว']);
    } else {
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $stmt->error]);
    }

    $stmt->close();
}
$conn->close();
?>
