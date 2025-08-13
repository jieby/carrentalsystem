<?php
session_start();
require 'db.php';

if (isset($_POST['toggle_status'])) {
    $car_id = $_POST['car_id'];

    $result = $conn->query("SELECT status FROM cars WHERE id = $car_id");
    $car = $result->fetch_assoc();

    $new_status = $car['status'] === 'available' ? 'maintenance' : 'available';

    $conn->query("UPDATE cars SET status = '$new_status' WHERE id = $car_id");

    header("Location: ../user/mainpage.php");
    exit;
}
