<?php 
session_start();
require '../backend/db.php';

if (isset($_POST['submit'])) {
    $user_id = $_POST['user_id'];
    $contact = $_POST['contact_number'];
    $license = $_POST['driver_license_id'];

    // Convert dates safely
    $pickup = date('Y-m-d', strtotime($_POST['pickup_date']));
    $return = date('Y-m-d', strtotime($_POST['return_date']));

    $car_id = $_POST['car_id'];
    $price_per_day = $_POST['price'];
    $total_price = $_POST['total_price'];
    $car_name = $_POST['car_name'];

    // Optional: validate inputs before insert...

    $stmt = $conn->prepare("INSERT INTO reservations (user_id, contact_number, driver_license_id, pickup_date, return_date, car_name, price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssdd", $user_id, $contact, $license, $pickup, $return, $car_name, $price_per_day, $total_price);

    if ($stmt->execute()) {
        header("Location: ../main.php?success=1");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    header("Location: ../main.php");
    exit;
}
