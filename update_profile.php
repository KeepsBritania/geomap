<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Simulate updating profile (you would save this to a database in a real system)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['bio'] = $_POST['bio'];
    
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $profilePic = $_FILES['profile_pic'];
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($profilePic["name"]);
        
        // Move the uploaded file
        if (move_uploaded_file($profilePic["tmp_name"], $targetFile)) {
            echo "Profile picture updated successfully.";
        } else {
            echo "Error uploading profile picture.";
        }
    }

    header('Location: dashboard.php');
    exit();
}
