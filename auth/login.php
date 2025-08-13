<?php
require '../backend/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "<script>alert('Email and password are required.'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("SELECT id, firstname, lastname, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'email' => $user['email']
            ];

            // ✅ Redirect on success
            header("Location: ../pages/mainpage.php");
            exit;
        }
    }

    echo "<script>alert('Invalid email or password.'); window.history.back();</script>";
    exit;
}
?>
