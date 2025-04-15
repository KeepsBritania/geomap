<?php
require 'db.php';
ini_set('display_errors', 1); // Make sure errors are visible

// Log every request for debugging
file_put_contents('log.txt', "Request: " . json_encode($_POST) . "\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;

    // Validate the received data
    if (!$id || !in_array($status, ['pending', 'ongoing', 'done'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid data received.']);
        exit();
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE markers SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    // Execute the statement and check if successful
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed.']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
