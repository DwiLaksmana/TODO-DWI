<?php
session_start();
require 'dbconnector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $deadline = $_POST['deadline'];
    $created_at = date('Y-m-d H:i:s');
    $user_id = $_SESSION['user_id']; // Make sure user is logged in

    $today = date('Y-m-d');
    $status = ($deadline < $today) ? 'past due' : 'pending';

    $sql = "INSERT INTO todos (user_id, title, deadline, created_at, status)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $title, $deadline, $created_at, $status);

    if ($stmt->execute()) {
        header("Location: homepage.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
