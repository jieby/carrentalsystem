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
    $id = intval($_GET['id']); // Sanitize input

    
    $res = $conn->query("SELECT * FROM reservations WHERE id = $id");
    $reservation = $res->fetch_assoc();

    if ($reservation) {
        $user_id = $reservation['user_id'];

        //user info
        $userRes = $conn->query("SELECT * FROM users WHERE id = $user_id");
        $user = $userRes->fetch_assoc();

        if ($user) {
            $name = isset($user['full_name']) ? $user['full_name'] : (isset($user['name']) ? $user['name'] : 'Customer');
            $email = $user['email'];

            // Update status to declined
            $update = $conn->query("UPDATE reservations SET status = 'declined' WHERE id = $id");

            if ($update) {
                $mail = new PHPMailer(true);
                try {
                    // SMTP Configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'jiebgaleno24@gmail.com';         // Gmail address
                    $mail->Password = 'lwjm kpdx wgia hgbl';            // Gmail App Password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Email content
                    $mail->setFrom('jiebgaleno24@gmail.com', 'Arnie Rent a Car');
                    $mail->addAddress($email, $name);
                    $mail->isHTML(true);
                    $mail->Subject = 'Reservation Declined';
                    $mail->Body = "Hi <strong>$name</strong>,<br><br>We regret to inform you that your car reservation has been <b>declined</b>.<br><br>If you have any questions, feel free to contact us.";

                    $mail->send();
                } catch (Exception $e) {
                    // Optional: log email error
                }

                // Redirect back to reservation list
                header("Location: booking_history.php");
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
