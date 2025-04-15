<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Marker fields
    $name = $_POST['name'];
    $address = $_POST['address'];
    $owner_manager = $_POST['owner_manager'];
    $num_of_personnel = $_POST['num_of_personnel'];
    $with_health_cert = $_POST['with_health_cert'];
    $sanitary_permit_no = $_POST['sanitary_permit_no'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    // Insert into markers table
    $stmt = $conn->prepare("INSERT INTO markers (name, address, owner_manager, num_of_personnel, with_health_cert, sanitary_permit_no, lat, lng) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiiidd", $name, $address, $owner_manager, $num_of_personnel, $with_health_cert, $sanitary_permit_no, $lat, $lng);
    $stmt->execute();
    $marker_id = $stmt->insert_id;

    // Inspection fields
    $cleaning_food_utensils = isset($_POST['cleaning_food_utensils']) ? 1 : 0;
    $food_protection = isset($_POST['food_protection']) ? 1 : 0;
    $inspected_by = $_POST['inspected_by'];
    $received_by = $_POST['received_by'];

    // Insert into inspections table
    $stmt2 = $conn->prepare("INSERT INTO inspections (marker_id, cleaning_food_utensils, food_protection, inspected_by, received_by) VALUES (?, ?, ?, ?, ?)");
    $stmt2->bind_param("iiiss", $marker_id, $cleaning_food_utensils, $food_protection, $inspected_by, $received_by);
    $stmt2->execute();

    header("Location: index.php");
    exit;
}
?>
