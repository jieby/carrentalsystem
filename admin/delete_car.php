<?php
require 'db.php';

if (isset($_POST['car_id'])) {
    $car_id = $_POST['car_id'];
    $conn->query("DELETE FROM cars WHERE id = $car_id");
    header("Location: ../admin/car_management.php");
    exit;
}
