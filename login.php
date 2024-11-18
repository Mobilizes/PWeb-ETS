<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
  <div id="login-container">
    <h2>Login</h2>
    <form method="POST" action="home.php">
      <label for="name">Name:</label>
      <input type="text" name="name" id="name" required><br><br>
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required><br><br>
      <button type="submit">Login</button><br><br>
      <?php
        if (isset($_SESSION["login-error"])) {
          echo "<span style='color: red;'>" . $_SESSION["login-error"] . "</span>";
          unset($_SESSION["login-error"]);
        }
      ?>
    </form>
    <h3>Don't have an account?</h3>
    <form method="GET" action="register.php">
      <button type="submit">Register</button>
    </form>
  </div>
</body>
</html>
