<?php
include 'db.php';

// Query the database to fetch addresses from the places table
$result = $conn->query("SELECT address FROM places WHERE city = 'Bago' ORDER BY address ASC");

$places = [];

// Loop through the results and add addresses to the $places array
while ($row = $result->fetch_assoc()) {
  $places[] = $row['address'];
}

// Return the addresses as a JSON response
echo json_encode($places);
?>
