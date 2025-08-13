<?php
require 'db.php';

if (isset($_POST['car_id'])) {
    $car_id = $_POST['car_id'];
    // For now: redirect back, future version pwedeng popup/modal
    header("Location: ../admin/edit_form.php?id=$car_id");
    exit;
}
