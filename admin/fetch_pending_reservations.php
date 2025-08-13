<?php
require '../backend/db.php';

$result = $conn->query("SELECT * FROM reservations WHERE status = 'pending' ORDER BY created_at DESC");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr data-id="' . $row['id'] . '" class="border-b">';
        echo '<td class="py-2 px-4">' . htmlspecialchars($row['full_name']) . '</td>';
        echo '<td class="py-2 px-4">' . htmlspecialchars($row['car_name']) . '</td>';
        echo '<td class="py-2 px-4">' . $row['pickup_date'] . '</td>';
        echo '<td class="py-2 px-4">' . $row['return_date'] . '</td>';
        echo '<td class="py-2 px-4">₱' . number_format($row['total_price'], 2) . '</td>';
        echo '<td class="py-2 px-4 flex items-center gap-2">';
        echo '<button onclick=\'openViewModal(' . json_encode($row, JSON_HEX_APOS) . ')\' class="bg-blue-500 text-white px-3 py-1 rounded">View</button>';
        echo '<a href="approve.php?id=' . $row['id'] . '" class="bg-green-500 text-white px-3 py-1 rounded">Approve</a>';
        echo '<a href="decline.php?id=' . $row['id'] . '" class="bg-red-500 text-white px-3 py-1 rounded">Decline</a>';
        echo '</td></tr>';
    }
} else {
    echo '<tr><td colspan="6" class="py-4 text-center text-gray-600">No pending requests found.</td></tr>';
}
