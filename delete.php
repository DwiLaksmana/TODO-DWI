<?php
include 'dbconnector.php';

// Check if the 'id' parameter is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Use prepared statement for security
    $stmt = $conn->prepare("DELETE FROM todos WHERE id = ?");
    $stmt->bind_param("i", $id);  // 'i' for integer
    $stmt->execute();
    
    // Redirect after deletion
    header("Location: homepage.php");
    exit();
} else {
    // If no id is provided, redirect to homepage
    header("Location: homepage.php");
    exit();
}
?>
