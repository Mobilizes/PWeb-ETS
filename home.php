<?php
session_start();

$mysqli = new mysqli(null,"root", "pass", "simple_todo_db", "3306");

if ($mysqli->connect_errno) {
  echo "". $mysqli->connect_error;
  exit();
}

if (isset($_SESSION["registering"]) && isset($_SESSION["name"]) && isset($_POST["password"])) {
  $name = htmlspecialchars($_POST["name"]);
  $password = htmlspecialchars($_POST["password"]);

  if ($mysqli->query("SELECT * FROM todo_users WHERE name = '$name'")->num_rows > 0) {
    $_SESSION["register-error"] = "Name already exists";
    header("Location: register.php");
  }

  $mysqli->query("INSERT INTO `todo_users` (`name`, `password`) VALUES ('$name', '$password');");

} else if (isset($_SESSION["registering"])) {
  $_SESSION["register-error"] = "Please input name and password!";
  header("Location: register.php");
}

unset($_SESSION["registering"]);

if (isset($_POST["name"]) && isset($_POST["password"])) {
  $name = htmlspecialchars($_POST["name"]);
  $password = htmlspecialchars($_POST["password"]);

  $sql = $mysqli->query("SELECT * FROM todo_users WHERE name = '$name' AND password = '$password'");
  if ($sql->num_rows > 0) {
    $row = $sql->fetch_assoc();
    $_SESSION["id"] = $row["id"];
    $_SESSION["name"] = $row["name"];
  } else {
    $_SESSION["login-error"] = "Invalid name or password";
    header("Location: login.php");
  }
} else if (!isset($_SESSION["id"])) {
  header("Location: login.php");
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Simple Todo App</title>
  <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
  <div id="title">Simple To-Do List</div><br>
  <div>
    <?php
    echo("Welcome, " . $_SESSION["name"] . "!<br>");
    ?>
    <button id="logout-button" onclick="location.href='logout.php'">Logout</button>
  </div>
  <div id="task-input-container">
    <form method="POST" action="add.php">
      <span>
        <input type="text" name="task-input" id="task-input" placeholder="What do you need to do?">
        <button id="add-task-button" type="submit">Add</button>
        <?php
        if (isset($_SESSION["task-input"])) {
          if ($_SESSION["task-input"] == "Invalid task input") {
            echo "<span style='color: red;'>Invalid task input</span>";
          } else if ($_SESSION["task-input"] == "Task added") {
            echo "<span style='color: green;'>Task added</span>";
          } else if ($_SESSION["task-input"] == "MySQL error") {
            echo "<span style='color: red;'>".$_SESSION["task-input"]["desc"]."</span>";
          }
          unset($_SESSION["task-input"]);
        }
        ?>
      </span>
    </form>
    <form method="POST" enctype="multipart/form-data" action="import.php">
      <span>
        <input type="file" style="margin-top: 10px" name="import" id="import" accept=".csv">
        <button id="import-button" type="submit">Import</button>
        <?php
        if (isset($_SESSION["import-error"])) {
          echo "<span style='color: red;'>".$_SESSION["import-error"]."</span>";
          unset($_SESSION["import-error"]);
        }
        ?>
      </span>
    </form>
    <button id="export-button" onclick="location.href='export.php'">Export</button>
  </div>
  <div id="task-list">
    <?php
    $sql = "SELECT * FROM tasks WHERE user_id = " . $_SESSION["id"];
    $result = $mysqli->query($sql);
    $ongoing = [];
    $finished = [];
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $status = $row['status'];
        if ($status == false) {
          $ongoing[] = $row;
        } else {
          $finished[] = $row;
        }
      }
    }

    if (count($ongoing) > 0) {
      foreach ($ongoing as $task) {
        echo "
        <div id='ongoing-task'>
          <input type='checkbox' class='task-status' data-id='{$task['id']}' data-status='{$task['status']}' " . ($task['status'] ? 'checked' : '') . ">
          <span class='task-name'>{$task['name']}</span>
          <div id='delete-task'>
            <form method='POST' action='delete.php'>
              <input type='hidden' name='id' value='{$task['id']}'>
              <button id='delete-task-button' type='submit'>Delete</button>
            </form>
          </div>
        </div>
        ";
      }
    }

    if (count($finished) > 0) {
      foreach ($finished as $task) {
        echo "
        <div id='finished-task'>
          <input type='checkbox' class='task-status' data-id='{$task['id']}' data-status='{$task['status']}' " . ($task['status'] ? 'checked' : '') . ">
          <span class='task-name'>{$task['name']}</span>
          <div id='delete-task'>
            <form method='POST' action='delete.php'>
              <input type='hidden' name='id' value='{$task['id']}'>
              <button id='delete-task-button' type='submit'>Delete</button>
            </form>
          </div>
        </div>
        ";
      }
    }
    ?>
  </div>
  <script src="public/js/script.js"></script>
</body>
</html>
