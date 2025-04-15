<?php
$conn = new mysqli("localhost", "root", "", "geoapp");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>