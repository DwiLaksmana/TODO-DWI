<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Login Page
    </title>
    <link rel="stylesheet" href="masuk.css">
</head>
<body>
      <nav class="navbar">
        <div class="logo">📝 ToDo App</div>
        <ul class="nav-links">
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php">Register</a></li>
        </ul>
      </nav>

    <div class="login-container">
            <h2>Login</h2>
        <form action="loginproses.php" method="POST">
            <div class="form-group">
                <input type="email" id="email" name="email" required>
                <label for="email" class="email">Email</label>
            </div>
            <div class="form-group">
            <input type="password" id="password" name="password" required autocomplete="off">
            <label for="password">Password</label>
            </div>
            <button type="submit" class="login-btn">Masuk</button>
        </form>
        <div class="form-group">
            <p>Belum Punya Akun? <a href="register.php">Daftar</a></p>
        </div>
    </div>
</body>
</html>