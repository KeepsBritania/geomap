<?php
require 'db.php';

$sql = "SELECT id, address, details, lat, lng, status FROM markers";
$result = $conn->query($sql);

$markers = [];

while ($row = $result->fetch_assoc()) {
    $markers[] = $row;
}

header('Content-Type: application/json');
echo json_encode($markers);
?>
