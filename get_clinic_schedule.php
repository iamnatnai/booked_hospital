<?php
// Database connection
$host = 'localhost';
$dbname = 'kasemra2_book';
$username = 'kasemra2_book';
$password = 'kasemrad@64';

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get clinic_id from the request
$clinic_id = isset($_GET['clinic_id']) ? intval($_GET['clinic_id']) : 0;

if ($clinic_id > 0) {
    // Query to fetch available clinic schedule (days of the week)
    $sql = "SELECT day_of_week FROM clinic_schedule WHERE clinic_id = ? AND status_day = 'ON'";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $clinic_id);
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->get_result();
    
    $availableDays = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $availableDays[] = $row['day_of_week']; // Store the day of the week
        }
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Return the available days as JSON
    echo json_encode($availableDays);
} else {
    echo json_encode([]); // If no clinic_id is provided, return an empty array
}
?>
