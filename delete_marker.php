<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Sanitize the ID
    $id = (int)$id;

    // Attempt to delete the marker
    $query = "DELETE FROM markers WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Return success as JSON
            echo json_encode(["success" => true]);
        } else {
            // Return error as JSON
            echo json_encode(["success" => false, "error" => "Failed to delete marker."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Failed to prepare SQL query."]);
    }
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request."]);
}
?>
