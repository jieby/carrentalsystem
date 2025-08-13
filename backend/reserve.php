<?php
require 'backend/db.php';

if (isset($_POST['submit'])) {
    $car_id = intval($_POST['car_id']);
    $full_name = $_POST['full_name'];
    $contact_number = $_POST['contact_number'];
    $pickup = $_POST['pickup_date'];
    $return = $_POST['return_date'];

    $pickup_date = new DateTime($pickup);
    $return_date = new DateTime($return);
    $interval = $pickup_date->diff($return_date)->days;
    $interval = max(1, $interval);

    $car_stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $car_stmt->bind_param("i", $car_id);
    $car_stmt->execute();
    $car_result = $car_stmt->get_result();
    $car = $car_result->fetch_assoc();

    if (!$car) {
        die("Invalid car selected.");
    }

    $price_per_day = $car['price_per_day'];
    $total_price = $interval * $price_per_day;

    $stmt = $conn->prepare("INSERT INTO reservations (full_name, contact_number, car_id, pickup_date, return_date, total_price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissd", $full_name, $contact_number, $car_id, $pickup, $return, $total_price);

    if ($stmt->execute()) {
        // Connect to WebSocket server and send notification
        $context = stream_context_create();
        $socket = @stream_socket_client("tcp://127.0.0.1:8080", $errno, $errstr, 1, STREAM_CLIENT_CONNECT, $context);

        if ($socket) {
            $message = json_encode([
                "type" => "new_reservation",
                "full_name" => $full_name,
                "car" => $car['name'] ?? 'N/A',
                "pickup_date" => $pickup,
                "return_date" => $return,
                "total_price" => $total_price
            ]);
            fwrite($socket, $message);
            fclose($socket);
        } else {
            error_log("WebSocket connection failed: $errstr ($errno)");
        }

        header("Location: success.php");
        exit;
    } else {
        die("Failed to create reservation.");
    }
}
?>
