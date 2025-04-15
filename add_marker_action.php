<?php
include 'db.php'; // Your database connection file

// Get the posted data
$address = isset($_POST['address']) ? $_POST['address'] : '';
$details = isset($_POST['details']) ? $_POST['details'] : '';
$lat = isset($_POST['lat']) ? $_POST['lat'] : '';
$lng = isset($_POST['lng']) ? $_POST['lng'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

// Sanitize inputs to prevent SQL injection
$address = mysqli_real_escape_string($conn, $address);

// Check if the address already exists in the database
$query = "SELECT * FROM markers WHERE address = '$address'";
$result = mysqli_query($conn, $query);

// If a record exists, return a response indicating a duplicate
if (mysqli_num_rows($result) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'This address already exists.']);
} else {
    // Insert the new marker into the database
    $insertQuery = "INSERT INTO markers (address, details, lat, lng, status) 
                    VALUES ('$address', '$details', '$lat', '$lng', '$status')";

    if (mysqli_query($conn, $insertQuery)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add marker.']);
    }
}

mysqli_close($conn);
?>
