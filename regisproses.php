<?php
session_start();
include 'dbconnector.php';

// Validate and get input values
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);

$targetPath = 'uploads/default.png'; // Make sure you have this file

// Check if profile picture is uploaded and has no error
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $fileType = $_FILES['profile_pic']['type'];
    $fileSize = $_FILES['profile_pic']['size'];

    // Only allow certain image types and size < 2MB
    if (!in_array($fileType, $allowedTypes) || $fileSize > 2 * 1024 * 1024) {
        echo "<script>alert('Invalid profile picture. Only JPG/PNG under 2MB allowed.'); window.location.href='register.php';</script>";
        exit();
    }

    // Save file
    $uploadsDir = 'uploads/';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true);
    }

    $filename = uniqid() . '_' . basename($_FILES['profile_pic']['name']);
    $targetPath = $uploadsDir . $filename;

    if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetPath)) {
        echo "<script>alert('Failed to upload profile picture.'); window.location.href='register.php';</script>";
        exit();
    }
}

// Save user to DB
$stmt = $conn->prepare("INSERT INTO user (profile_pic, username, email, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $targetPath, $username, $email, $password);

if ($stmt->execute()) {
    echo "<script>alert('Registration successful! Please login.'); window.location.href='login.php';</script>";
} else {
    echo "<script>alert('Error: Email may already be in use.'); window.location.href='register.php';</script>";
}

$stmt->close();
$conn->close();
?>
