<?php
session_start();
$mysqli = new mysqli("localhost", "root", "pass", "simple_todo_db", "3306");

if ($mysqli->connect_errno) {
  echo json_encode(['success' => false, 'message' => 'Failed to connect to MySQL: ' . $mysqli->connect_error]);
  exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$status = $data['status'];

$sql = "UPDATE tasks SET status = ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ii', $status, $id);

if ($stmt->execute()) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Failed to update task status']);
}

$stmt->close();
$mysqli->close();
