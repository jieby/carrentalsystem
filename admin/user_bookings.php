<?php
session_start();
require '../backend/db.php';

if (!isset($_GET['user_id'])) {
    die("Invalid request");
}

$user_id = (int) $_GET['user_id'];

$user = $conn->query("SELECT firstname, lastname FROM users WHERE id = $user_id")->fetch_assoc();
$bookings = $conn->query("SELECT * FROM reservations WHERE user_id = $user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?> - Booking History</title>
    <link href="../output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-6">
    Booking History - <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>
</h1>

<?php if ($bookings->num_rows > 0): ?>
<div class="overflow-x-auto">
    <table class="min-w-full bg-white shadow rounded-lg overflow-hidden">
        <thead class="bg-gray-200 text-left text-sm uppercase tracking-wider">
            <tr>
                <th class="py-3 px-4">Car</th>
                <th class="py-3 px-4">Pickup</th>
                <th class="py-3 px-4">Return</th>
                <th class="py-3 px-4">Price</th>
                <th class="py-3 px-4">Status</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($b = $bookings->fetch_assoc()): ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4"><?= htmlspecialchars($b['car_name']) ?></td>
                <td class="py-3 px-4"><?= date("M d, Y h:i A", strtotime($b['pickup_date'])) ?></td>
                <td class="py-3 px-4"><?= date("M d, Y h:i A", strtotime($b['return_date'])) ?></td>
                <td class="py-3 px-4">₱<?= number_format($b['total_price'], 2) ?></td>
                <td class="py-3 px-4"><?= ucfirst($b['status']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<p class="text-gray-600">No bookings found.</p>
<?php endif; ?>

</body>
</html>
