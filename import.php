<?php
session_start();

$mysqli = new mysqli(null, "root", "pass", "simple_todo_db", "3306");

if ($mysqli->connect_errno) {
  $_SESSION["import-error"] = $mysqli->connect_error;
  header("Location: index.php");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php');
}

if (empty($_FILES)) {
  $_SESSION['import-error'] = 'File is empty';
  header('Location: index.php');
}

if ($_FILES['import']['error'] !== UPLOAD_ERR_OK) {
  switch ($_FILES['import']['error']) {
    case UPLOAD_ERR_PARTIAL:
      $_SESSION['import-error'] = 'The uploaded file was only partially uploaded';
    case UPLOAD_ERR_NO_FILE:
      $_SESSION['import'] = 'No file was uploaded';
    case UPLOAD_ERR_NO_TMP_DIR:
      $_SESSION['import-error'] = 'No temporary directory was found';
    case UPLOAD_ERR_CANT_WRITE:
      $_SESSION['import-error'] = 'Failed to write file to disk';
    case UPLOAD_ERR_EXTENSION:
      $_SESSION['import-error'] = 'A PHP extension stopped the file upload';
    case UPLOAD_ERR_FORM_SIZE:
      $_SESSION['import-error'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
    case UPLOAD_ERR_INI_SIZE:
      $_SESSION['import-error'] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
    default:
      $_SESSION['import-error'] = 'Unknown upload error';
    
    header('Location: index.php');
  }
}

$fileTmpPath = $_FILES['import']['tmp_name'];
$fileTmpName = $_FILES['import']['name'];
$fileSize = $_FILES['import']['size'];
$fileExtension = $_FILES['import']['type'];

if (strtolower($fileExtension) !== 'text/csv') {
  $_SESSION['import-error'] = 'Invalid file type';
  header('Location: index.php');
}

if (($handle = fopen($fileTmpPath,'r')) === false) {
  $_SESSION['import-error'] = 'Failed to open file';
  header('Location: index.php');
}

// Export has no header
// fgetcsv($handle);

while (($data = fgetcsv($handle, 1000, ',')) !== false) {
  $id = $mysqli->real_escape_string($data[0]);
  $name = $mysqli->real_escape_string($data[1]);
  $description = $mysqli->real_escape_string($data[2]);
  $status = $mysqli->real_escape_string($data[3]);
  $created_at = $mysqli->real_escape_string($data[4]);
  $deadline = $mysqli->real_escape_string($data[5]);

  while ($mysqli->query('SELECT * FROM tasks WHERE id = ' . $id)->num_rows > 0) {
    $id++;
  }

  $sql = "INSERT INTO tasks (id, name, description, status, created_at) VALUES ('$id', '$name', '$description', '$status', '$created_at')";
  $mysqli->query($sql);
}

$mysqli->close();
header('Location: index.php');
