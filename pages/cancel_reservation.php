<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

require '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    $reservationId = intval($_POST['reservation_id']);
    $userId = $_SESSION['user']['id'];

    // Get reservation info
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $reservationId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $reservation = $result->fetch_assoc();

    if ($reservation) {
        $createdAt = strtotime($reservation['created_at']);
        $now = time();
        $diffInMinutes = ($now - $createdAt) / 60;

        if ($diffInMinutes <= 30) {
            // Proceed with cancellation
            $delete = $conn->prepare("DELETE FROM reservations WHERE id = ?");
            $delete->bind_param("i", $reservationId);
            $delete->execute();

            header("Location: ../pages/profile.php?msg=cancelled");
            exit;
        } else {
            header("Location: ../pages/profile.php?error=too_late");
            exit;
        }
    } else {
        header("Location: ../pages/profile.php?error=not_found");
        exit;
    }
}
