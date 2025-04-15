<?php
include 'db.php';

$sql = "SELECT * FROM markers";
$result = $conn->query($sql);

$markers = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $markers[] = $row;
  }
}

header('Content-Type: application/json');
echo json_encode(['markers' => $markers]); // this is important!
