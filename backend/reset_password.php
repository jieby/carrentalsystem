<?php
session_start();
require '../backend/db.php';

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = (int) $_GET['id'];

// Generate random temporary password
$tempPassword = bin2hex(random_bytes(4)); // 8-char
$hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

// Update DB
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $hashedPassword, $id);
$stmt->execute();

// TODO: send $tempPassword to user via email (use PHPMailer)

header("Location: ../admin/admin_users.php");
exit;
