<?php
$servername = "localhost";
$username = "kasemra2_book";
$password = "kasemrad@64";
$dbname = "kasemra2_book";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
