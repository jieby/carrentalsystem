<?php
require 'db.php';

if (isset($_POST['add_car'])) {
    $name = $_POST['name'];
    $image = $_POST['image'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO cars (name, image, price, status) VALUES (?, ?, ?, 'available')");
    $stmt->bind_param("ssi", $name, $image, $price);
    $stmt->execute();

    header("Location: ../admin/car_management.php");
    exit;
}
