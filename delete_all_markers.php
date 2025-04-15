<?php
include 'db.php';
mysqli_query($conn, "DELETE FROM markers"); // Assuming your table is named 'markers'
echo 'success';
?>