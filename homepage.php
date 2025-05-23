<?php
session_start();
include 'dbconnector.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 🔹 Fetch user info
$userStmt = $conn->prepare("SELECT profile_pic, username, email, password FROM user WHERE id = ?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userData = $userResult->fetch_assoc();
$userStmt->close();

if (empty($userData['profile_pic']) || !file_exists($userData['profile_pic'])) {
    $userData['profile_pic'] = 'uploads/default.png';
}


$censoredPassword = str_repeat("*", strlen($userData['password']));

// 🔹 Fetch todos for this user
$searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : null;

if ($searchTerm) {
    $stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ? AND title LIKE ? ORDER BY created_at DESC");
    $stmt->bind_param("is", $user_id, $searchTerm);
} else {
    $stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ToDo List</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="navbar-username">
                <?= htmlspecialchars($userData['username']) ?>'s ToDo List
            </div>
            <div id="profile-card" class="profile-card hidden">
                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                    <label for="profilePicInput">
                        <img src="<?= htmlspecialchars($userData['profile_pic']) ?>" alt="Profile Picture" class="profile-preview-img" />
                    </label>
                    <input type="file" name="profile_pic" id="profilePicInput" hidden>

                    <p><strong>Username:</strong></p>
                    <input type="text" name="username" value="<?= htmlspecialchars($userData['username']) ?>" required>

                    <p><strong>Email:</strong></p>
                    <input type="text" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required>

                    <p><strong>Current Password:</strong></p>
                    <input type="password" name="current_password" placeholder="Enter current password">

                    <p><strong>New Password:</strong></p>
                    <input type="password" name="new_password" placeholder="New password">

                    <div style="margin-top: 10px;">
                        <button type="submit" name="update_profile">Save Changes</button>
                    </div>

                    <div style="margin-top: 10px;">
                        <a href="logout.php" class="logout-button">Logout</a>
                    </div>
                </form>
            </div>

            <div class="profile-pic-wrapper">
                <img src="<?= htmlspecialchars($userData['profile_pic']) ?>" alt="Profile" class="profile-pic" onclick="toggleProfileCard()" />
            </div>
        </nav>
    </header>

    <main>
        <section class="todo-list" aria-label="ToDo List Section">
            <h1>
                <img src="nasgor.jpg" alt="tidak ada gambar"/>
                <span class="judul">ToDo List</span>
            </h1>

            <form method="GET">
                <div>
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>" />
                </div>
                <div>
                    <input type="hidden" name="status" value="all" />
                </div>
                <div>
                    <input type="hidden" name="sort" value="created_at" />
                </div>
                <!-- this is search todo -->
              <div>
                   <input type="search" name="search" placeholder="Cari todo..." aria-label="Search todo" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
                   <button type="submit" class="search-button">Cari</button>
              </div>
            </form>

            <form action="create.php" method="POST">
                <div>
                    <input type="text" name="title" placeholder="Apa yang ingin kamu kerjakan?" required />
                    <input type="date" name="deadline" required />
                    <button type="submit" name="submit" class="add-btn">Tambah</button>
                </div>
            </form>

            <ul class="todo-items" role="list">
            <?php if ($result->num_rows > 0): ?>
               <?php while($row = $result->fetch_assoc()):
                      $today = date('Y-m-d');
                      $isPastDue = $row['status'] === 'past due' && $row['deadline'] < $today;
                      $isDone = $row['status'] === 'done'; // Check if the task is marked as done
                      $liClass = '';
                      if ($isPastDue) {
                          $liClass = 'past-due';
                      } elseif ($isDone) {
                          $liClass = 'done'; // Add class for done tasks
                      }
                     ?>
                       <li class="<?= $liClass ?>" aria-label="Todo item <?= htmlspecialchars($row['title']) ?>">
                            <div>
                              <strong>
                                <?= htmlspecialchars($row['title']) ?>
                                <?php if ($isPastDue): ?>
                                    <span class="past-due-label">⚠️ TERLAMBAT</span>
                                <?php endif; ?>
                              </strong><br>
                              Status: <?= htmlspecialchars($row['status']) ?> <?= $isDone ? '✅' : '⏳' ?><br>
                              Deadline: <?= htmlspecialchars($row['deadline']) ?><br>
                              Created: <?= htmlspecialchars($row['created_at']) ?>
                            </div>
                            <div>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="edit-btn" aria-label="Edit todo">✏️</a>
                                <a href="done.php?id=<?= $row['id'] ?>" class="done-btn" aria-label="Mark as done">✔️</a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirmDelete();">❌</a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>No todos found.</li>
                <?php endif; ?>
            </ul>
        </section>
    </main>
    <script>
  function confirmDelete() {
    // Ask for confirmation before deleting
    return confirm("Are you sure you want to delete this todo?");
  }
  function toggleProfileCard() {
    const card = document.getElementById('profile-card');
    card.classList.toggle('hidden');
}

window.addEventListener('click', function(e) {
    const card = document.getElementById('profile-card');
    const img = document.querySelector('.profile-pic');

    if (!card.contains(e.target) && !img.contains(e.target)) {
        card.classList.add('hidden');
    }
});
</script>

</body>
</html>
