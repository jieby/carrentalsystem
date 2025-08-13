<?php
session_start();
require '../backend/db.php';

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Get reservation
    $res = $conn->query("SELECT * FROM reservations WHERE id = $id");
    $reservation = $res->fetch_assoc();

    if ($reservation) {
        $user_id = $reservation['user_id'];

        // Get user info
        $userRes = $conn->query("SELECT * FROM users WHERE id = $user_id");
        $user = $userRes->fetch_assoc();

        if ($user) {
            $name = isset($user['full_name']) ? $user['full_name'] : (isset($user['name']) ? $user['name'] : 'Customer');
            $email = $user['email'];

            $update = $conn->query("UPDATE reservations SET status = 'approved' WHERE id = $id");

            if ($update) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'jiebgaleno24@gmail.com';
                    $mail->Password = 'lwjm kpdx wgia hgbl';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('jiebgaleno24@gmail.com', 'Arnie Rent a Car');
                    $mail->addAddress($email, $name);
                    $mail->isHTML(true);
                    $mail->Subject = 'Reservation Approved';
                    $mail->Body = "Hi <strong>$name</strong>,<br><br>Your car reservation has been <b>approved</b>.<br><br>Thank you for choosing our service!";

                    $mail->send();
                } catch (Exception $e) {
                    // Optional: log the email error
                }
                header("Location: booking_history.php"); // Redirect after success
                exit;
            } else {
                echo "Failed to update reservation.";
            }
        } else {
            echo "User not found.";
        }
    } else {
        echo "Reservation not found.";
    }
} else {
    echo "No reservation ID provided.";
}
