<?php
session_start();

$mysqli = new mysqli(null,"root","pass","simple_todo_db","3306");

if ($mysqli->connect_errno) {
  echo "". $mysqli->connect_error;
  exit();
}

$result = $mysqli->query("SELECT * FROM tasks");

if (!$result) {
  echo "". $mysqli->error;
  exit();
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=tasks.csv');

$output = fopen('php://output', 'w');

if ($result->num_rows > 0) {
  $fields = $result->fetch_fields();

  while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
  }
}

fclose($output);
$mysqli->close();
exit();
