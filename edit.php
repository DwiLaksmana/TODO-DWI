<?php
// Include the database connection file
include 'dbconnector.php';
session_start(); // Make sure the session is started to access $_SESSION['user_id']

if (isset($_GET['id'])) {
    $todo_id = $_GET['id'];

    // Prepare and execute the query to fetch the todo item
    $stmt = $conn->prepare("SELECT title, deadline FROM todos WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $todo_id, $_SESSION['user_id']); // Make sure the user_id matches the session's user
    $stmt->execute();
    $result = $stmt->get_result();
    $todo = $result->fetch_assoc();

    if (!$todo) {
        // If the todo does not exist or doesn't belong to the user, redirect to homepage
        header("Location: homepage.php");
        exit();
    }

    // Fetch the title and deadline for editing
    $title = $todo['title'];
    $deadline = $todo['deadline'];
} else {
    // Redirect to homepage if no todo_id is provided in the URL
    header("Location: homepage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit ToDo</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>
    <div class="card">
        <h2>Edit ToDo</h2>
        <form action="update.php" method="POST">
            <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" placeholder="Nama Todo" required />
            <input type="date" name="deadline" value="<?php echo htmlspecialchars($deadline); ?>" placeholder="Tanggal Deadline" required />
            <div class="btn-group">
                <button class="btn save" type="submit">Simpan</button>
                <button class="btn cancel" type="button" onclick="window.location.href='homepage.php'">Kembali</button>
            </div>
            <!-- Hidden field to pass the todo_id to update.php -->
            <input type="hidden" name="id" value="<?php echo $todo_id; ?>" />
        </form>
    </div>
</body>
</html>
