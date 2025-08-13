<?php
require '../backend/db.php';

$carId = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;
$disabledDates = [];

if ($carId > 0) {
    $stmt = $conn->prepare("SELECT pickup_date, return_date FROM reservations WHERE car_id = ?");
    $stmt->bind_param("i", $carId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $disabledDates[] = [
            'from' => $row['pickup_date'],
            'to'   => $row['return_date']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($disabledDates);
