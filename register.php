<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=, initial-scale=1.0">
      <title>Register Page
      </title>
      <link rel="stylesheet" href="masuk.css">
  </head>
  <body>
    
      <nav class="navbar">
        <div class="logo">ToDo App</div>
        <ul class="nav-links">
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php">Register</a></li>
        </ul>
      </nav>

      <div class="login-container">
              <h2>Register</h2>
         <form action="regisproses.php" id="registerform" method="POST" enctype="multipart/form-data">
           <div class="form-group">
              <input type="text" id="username" name="username">
              <label for="username">Username</label>
           </div>
           <div class="form-group">
              <input type="email" id="email" name="email" required>
              <label for="email">Email</label>
           </div>
           <div class="form-group">
              <input type="password" id="password" name="password" required>
              <label for="password">Password</label>
           </div>
            <div class="form-group">
                <input type="password" id="confirmPassword" required>
                <label for="password">Confirm Password</label>
            </div>
            <div class="form-group">
                <label for="profile_pic">Upload Picture</label>
                <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
            </div>

            <div id="error" class="error"></div>
            <button type="submit" class="register-btn">Daftar</button>
        </form>
          <div class="form-group">
              <p>Sudah Punya Akun? <a href="login.php">Masuk</a></p>
          </div>
      </div>

    
    <script>
  const form = document.getElementById('registerform');
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('confirmPassword');
  const errorDiv = document.getElementById('error');

  form.addEventListener('submit', function(e) {
    if (password.value !== confirmPassword.value) {
      e.preventDefault(); // Stop form from submitting
      errorDiv.textContent = "Password harus sama!";
    } else {
      errorDiv.textContent = "";
    }
  });
</script>


  </body>
</html>