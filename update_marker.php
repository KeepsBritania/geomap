<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the required data is present
    if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['address']) && isset($_POST['owner_manager'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $owner_manager = $_POST['owner_manager'];
        $num_of_personnel = $_POST['num_of_personnel'];
        $with_health_cert = $_POST['with_health_cert'];
        $sanitary_permit_no = $_POST['sanitary_permit_no'];
        $cleaning_food_utensils = isset($_POST['cleaning_food_utensils']) ? 1 : 0;
        $food_protection = isset($_POST['food_protection']) ? 1 : 0;
        $inspected_by = $_POST['inspected_by'];
        $received_by = $_POST['received_by'];
        $details = $_POST['details'];
        $status = $_POST['status'];

        // Prepare the query
        $query = "UPDATE markers SET 
                name = ?, 
                address = ?, 
                owner_manager = ?, 
                num_of_personnel = ?, 
                with_health_cert = ?, 
                sanitary_permit_no = ?, 
                cleaning_food_utensils = ?, 
                food_protection = ?, 
                inspected_by = ?, 
                received_by = ?, 
                details = ?, 
                status = ? 
                WHERE id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssiisssssssi", 
                        $name, 
                        $address, 
                        $owner_manager, 
                        $num_of_personnel, 
                        $with_health_cert, 
                        $sanitary_permit_no, 
                        $cleaning_food_utensils, 
                        $food_protection, 
                        $inspected_by, 
                        $received_by, 
                        $details, 
                        $status, 
                        $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Marker updated successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing data']);
    }
}
?>
