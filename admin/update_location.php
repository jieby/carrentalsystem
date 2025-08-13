<?php
require '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = intval($_POST['car_id']);
    $lat = floatval($_POST['latitude']);
    $lng = floatval($_POST['longitude']);

    $stmt = $conn->prepare("UPDATE cars SET latitude=?, longitude=? WHERE id=?");
    $stmt->bind_param("ddi", $lat, $lng, $car_id);
    $stmt->execute();
    echo "Updated";
}
?>
