<?php
$servername = "localhost";
$username = "root";  // Use your MySQL username
$password = "";      // Use your MySQL password
$dbname = "gcash_clone";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>