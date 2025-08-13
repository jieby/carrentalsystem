<?php
session_start();
require '../backend/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $car_id = $_POST['car_id'];
    $car_name = $conn->real_escape_string($_POST['car_name']);
    $price_per_day = floatval($_POST['price']);
    $total_price = floatval($_POST['total_price']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $email = $conn->real_escape_string($_POST['email']);
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $pickup_date = $_POST['pickup_date'];
    $return_date = $_POST['return_date'];
    $drive_option = $_POST['drive_option'];
    $license_id = isset($_POST['driver_license_id']) ? $conn->real_escape_string($_POST['driver_license_id']) : null;
    $gov_id = isset($_POST['gov_id']) ? $conn->real_escape_string($_POST['gov_id']) : null;

    // 1. Check kung may existing reservation na may overlapping dates
    $check = $conn->prepare("
        SELECT id FROM reservations
        WHERE car_id = ?
        AND status IN ('pending', 'approved') 
        AND (
            (pickup_date <= ? AND return_date >= ?) OR
            (pickup_date <= ? AND return_date >= ?) OR
            (? <= pickup_date AND ? >= return_date)
        )
    ");
    $check->bind_param("issssss", 
        $car_id, 
        $return_date, $pickup_date, 
        $pickup_date, $return_date, 
        $pickup_date, $return_date
    );
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // May conflict, wag i-insert
        echo "<script>alert('Sorry, this car is already reserved for the selected dates.'); window.history.back();</script>";
        exit;
    }
    $check->close();

    // 2. Insert reservation kung walang conflict
    $stmt = $conn->prepare("INSERT INTO reservations 
        (user_id, car_id, car_name, price_per_day, total_price, contact_number, email, full_name, pickup_date, return_date, drive_option, driver_license_id, gov_id, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    
    $stmt->bind_param(
        "iisdsssssssss", 
        $user_id, $car_id, $car_name, $price_per_day, $total_price,
        $contact_number, $email, $full_name, $pickup_date, $return_date,
        $drive_option, $license_id, $gov_id
    );

    if ($stmt->execute()) {
        header("Location: ../admin/pendingrequest.php");
        exit;
    } else {
        echo "Reservation failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../pages/mainpage.php");
    exit;
}
?>
