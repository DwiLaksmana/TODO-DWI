<?php
// Include the database connection file
include 'dbconnector.php';
session_start(); // Start session to access user_id

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the values from the form
    $todo_id = $_POST['id'];  // The id of the todo being edited
    $title = $_POST['title'];  // The new title from the form
    $deadline = $_POST['deadline'];  // The new deadline from the form

    // Determine the status based on the new deadline
    $current_date = date("Y-m-d");  // Current date
    $status = 'pending';  // Default status is 'pending'

    // If the deadline is in the past, set status to 'past_due'
    if ($deadline < $current_date) {
        $status = 'past due';
    }

    // Prepare the SQL statement to update the todo item
    $stmt = $conn->prepare("UPDATE todos SET title = ?, deadline = ?, status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sssii", $title, $deadline, $status, $todo_id, $_SESSION['user_id']);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to homepage after successful update
        header("Location: homepage.php");
        exit();
    } else {
        // Error handling if the update fails
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>
