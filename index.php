<?php
session_start();

$mysqli = new mysqli(null,"root", "pass", "simple_todo_db", "3306");

if ($mysqli->connect_errno) {
  echo "". $mysqli->connect_error;
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Simple Todo App</title>
  <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
  <div id="title">Simple To-Do List</div>
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
    $sql = "SELECT * FROM tasks";
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
