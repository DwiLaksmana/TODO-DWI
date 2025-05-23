<?php
session_start();
include 'dbconnector.php'; 

// Get email and password from form
$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Prepare the SQL statement
$sql = "SELECT id, password FROM user WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Check if a user was found
if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $storedPassword);
    $stmt->fetch();

    // Compare plain text passwords
    if ($password === $storedPassword) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_email'] = $email;
        $stmt->close();
        $conn->close();
        echo "<script>alert('Login successful!'); window.location.href='homepage.php';</script>";
        exit();
    } else {
        // Password is wrong
        echo "<script>alert('Incorrect email or password'); window.location.href='login.php';</script>";
    }
} else {
    // Email not found
    echo "<script>alert('Incorrect email or password'); window.location.href='login.php';</script>";
}

$stmt->close();
$conn->close();
?>
