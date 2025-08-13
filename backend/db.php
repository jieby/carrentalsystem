<?php
$conn = new mysqli("localhost", "root", "", "carrental");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
