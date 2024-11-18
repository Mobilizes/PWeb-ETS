<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <title>register</title>
  <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
  <div id="register-container">
    <h2>Register</h2>
    <form method="POST" action="home.php">
      <label for="name">Name:</label>
      <input type="text" name="name" id="name" required><br><br>
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required><br><br>
      <button type="submit">Register</button><br><br>
      <?php
        $_SESSION["registering"] = "true";
        if (isset($_SESSION["register-error"])) {
          echo "<span style='color: red;'>" . $_SESSION["register-error"] . "</span>";
          unset($_SESSION["register-error"]);
        }
      ?>
    </form>
    <h3>Already have an account?</h3>
    <form method="GET" action="login.php">
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
