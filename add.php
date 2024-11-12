<?php
session_start();
$mysqli = new mysqli(null, "root", "pass", "simple_todo_db", "3306");

if ($mysqli->connect_errno) {
  echo "". $mysqli->connect_error;
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $task = $mysqli->real_escape_string($_POST['task-input']);
  if (!valid_input($task)) {
    $_SESSION["task-input"] = "Invalid task input";
    header("Location: home.php");
    exit();
  }

  $sql = "INSERT INTO tasks (name, status, user_id) VALUES ('$task', 0, ".$_SESSION['id'].")";
  if ($mysqli->query($sql) === TRUE) {
    $_SESSION["task-input"] = "Task added";
  } else {
    $_SESSION["task-input"] = "MySQL error";
    $_SESSION["task-input"]["desc"] = $mysqli->error;
  }

  header("Location: home.php");
}

function valid_input($input) {
  return !empty($input);
}
