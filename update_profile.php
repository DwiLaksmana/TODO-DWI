<?php
session_start();
include 'dbconnector.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$query = $conn->prepare("SELECT * FROM user WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
$query->close();

if (isset($_POST['update_profile'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $current_pw = $_POST['current_password'];
    $new_pw = $_POST['new_password'];
    $profile_pic = $user['profile_pic']; // keep existing pic if not changed

    // Handle profile picture upload
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/";
        $unique_name = uniqid() . "_" . basename($_FILES['profile_pic']['name']);
        $target_file = $target_dir . $unique_name;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);
        $profile_pic = $target_file;
    }

    // Handle password change
    if (!empty($current_pw) && !empty($new_pw)) {
        if ($current_pw === $user['password']) {
            $final_pw = $new_pw;
        } else {
            die("⚠️ Incorrect current password.");
        }
    } else {
        $final_pw = $user['password']; // retain old password
    }

    // Update profile
    $stmt = $conn->prepare("UPDATE user SET username = ?, email = ?, profile_pic = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $username, $email, $profile_pic, $final_pw, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: homepage.php");
    exit;
}
?>
