<?php
session_start();
include 'config.php'; // Include your database connection file

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Function to handle login
function login($pdo, $citizen_id, $dob) {
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE national_id = :citizen_id AND birth_date = :dob");
    $stmt->bindParam(':citizen_id', $citizen_id);
    $stmt->bindParam(':dob', $dob);

    // Execute the query
    if ($stmt->execute()) {
        return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row instead of multiple
    } else {
        error_log("SQL Error: " . implode(", ", $stmt->errorInfo())); // Log error details
        return false; // Query error
    }
}

// Process login when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json'); // Set the header for JSON response
    $citizen_id = sanitizeInput($_POST['citizen_id']);
    $dob_day = sanitizeInput($_POST['dob_day']);
    $dob_month = sanitizeInput($_POST['dob_month']);
    $dob_year = sanitizeInput($_POST['dob_year']);

    // Combine DOB into a single string
    $dob = "$dob_year-$dob_month-$dob_day";

    // Attempt login
    $result = login($pdo, $citizen_id, $dob);
    $response = [];

    if ($result !== false) {
        if ($result) { // Check if a row was returned
            // Login successful - Store retrieved data in session
            $_SESSION['citizen'] = $citizen_id; // Store citizen_id in session

            // Store the other patient data in session
            $_SESSION['national_id'] = $result['national_id'];
            $_SESSION['birth_date'] = $result['birth_date'];
            $_SESSION['first_name'] = $result['first_name'];
            $_SESSION['last_name'] = $result['last_name'];
            $_SESSION['phone_number'] = $result['phone_number'];
            $_SESSION['blood_thinner'] = $result['blood_thinner'];
            $_SESSION['medical_conditions'] = $result['medical_conditions'];

            // Set success response and redirect URL
            $response['success'] = true;
            $response['redirectUrl'] = 'index.php'; // Redirect to dashboard
        } else {
            // Incorrect credentials
            $response['success'] = false;
            $response['message'] = "Incorrect Citizen ID or Date of Birth.";
        }
    } else {
        // Query error
        $response['success'] = false;
        $response['message'] = "An error occurred during login. Please try again.";
    }

    echo json_encode($response);
    exit();
}
