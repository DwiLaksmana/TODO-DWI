<?php
// Database connection details
$host = 'localhost';
$username = 'root'; // Default username for phpMyAdmin
$password = ''; // Default password for phpMyAdmin (empty by default)
$database = 'todo';

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
