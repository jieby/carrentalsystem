<?php
session_start();
require '../backend/db.php';

if (!isset($_GET['id'], $_GET['action'])) {
    die("Invalid request");
}

$id = (int) $_GET['id'];
$action = $_GET['action'] === 'activate' ? 'active' : 'deactivated';

$stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
$stmt->bind_param("si", $action, $id);
$stmt->execute();

header("Location: ../admin/admin_users.php");
exit;
