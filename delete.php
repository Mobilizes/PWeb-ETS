<?php
session_start();
$mysqli = new mysqli(null, "root", "pass", "simple_todo_db", "3306");

if ($mysqli->connect_error) {
  echo "". $mysqli->connect_error;
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $mysqli->real_escape_string($_POST['id']);
  $sql = "DELETE FROM tasks WHERE id = $id";
  if ($mysqli->query($sql) === TRUE) {
    header("Location: home.php");
  } else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
  }
}
