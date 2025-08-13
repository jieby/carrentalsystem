<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

$user = $_SESSION['user'];
require '../backend/db.php';

$reservations = $conn->prepare("SELECT r.*, c.name AS car_name FROM reservations r JOIN cars c ON r.car_id = c.id WHERE r.user_id = ? ORDER BY r.created_at DESC");
$reservations->bind_param("i", $user['id']);
$reservations->execute();
$result = $reservations->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Profile</title>
  <link href="../output.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

  <header class="flex justify-between items-center mb-8">
    <h1 class="text-2xl font-bold">Your Reservations</h1>
    <a href="../pages/Mainpage.php" class="text-blue-500 hover:underline">← Back to Main</a>
  </header>

  <?php if ($result->num_rows > 0): ?>
    <div class="space-y-4">
      <?php while ($row = $result->fetch_assoc()):
        $createdAt = strtotime($row['created_at']);
        $now = time();
        $diffInMinutes = ($now - $createdAt) / 60;
        $canCancel = $diffInMinutes <= 30;
      ?>
        <div class="bg-white p-4 rounded shadow">
          <div class="mb-2">
            <span class="font-semibold">Car:</span> <?= htmlspecialchars($row['car_name']) ?>
          </div>
          <div class="mb-2">
            <span class="font-semibold">Pickup:</span> <?= htmlspecialchars($row['pickup_date']) ?> |
            <span class="font-semibold">Return:</span> <?= htmlspecialchars($row['return_date']) ?>
          </div>
          <div class="mb-2">
            <span class="font-semibold">Drive Option:</span> <?= htmlspecialchars($row['drive_option']) ?>
          </div>
          <div class="mb-2">
            <span class="font-semibold">Reserved At:</span> <?= date("F j, Y g:i A", $createdAt) ?>
          </div>
          <div class="mb-2">
            <span class="font-semibold">Total Price:</span> ₱<?= number_format($row['total_price'], 2) ?>
          </div>
          <?php if ($canCancel): ?>
            <form action="cancel_reservation.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
              <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">
              <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Cancel Reservation</button>
            </form>
          <?php else: ?>
            <p class="text-sm text-gray-500 italic">Cannot cancel (30-minute window expired)</p>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p>No reservations found.</p>
  <?php endif; ?>

</body>
</html>
