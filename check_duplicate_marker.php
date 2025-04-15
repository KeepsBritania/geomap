<?php
// check_duplicate_marker.php

include 'db.php'; // Your database connection file

// Get the address from the POST request
$address = isset($_POST['address']) ? $_POST['address'] : '';

// Sanitize the input address to prevent SQL injection
$address = mysqli_real_escape_string($conn, $address);

// Check if the address already exists in the markers table
$query = "SELECT * FROM markers WHERE address = '$address'";
$result = mysqli_query($conn, $query);

// If a record exists, return a response indicating that it's a duplicate
if (mysqli_num_rows($result) > 0) {
    echo json_encode(['exists' => true]);
} else {
    echo json_encode(['exists' => false]);
}

mysqli_close($conn);
?>
