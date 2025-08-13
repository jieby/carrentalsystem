<?php
session_start();
require '../backend/db.php';

// Get all users
$result = $conn->query("SELECT id, firstname, lastname, email, status, created_at FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link href="../output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-6">User Management</h1>

<div class="overflow-x-auto">
    <table class="min-w-full bg-white shadow rounded-lg overflow-hidden">
        <thead class="bg-gray-200 text-left text-sm uppercase tracking-wider">
            <tr>
                <th class="py-3 px-4">Name</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Date Created</th>
                <th class="py-3 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($user = $result->fetch_assoc()): ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></td>
                <td class="py-3 px-4"><?= htmlspecialchars($user['email']) ?></td>
                <td class="py-3 px-4">
                    <?php if ($user['status'] === 'active'): ?>
                        <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Active</span>
                    <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Deactivated</span>
                    <?php endif; ?>
                </td>
                <td class="py-3 px-4"><?= date("M d, Y", strtotime($user['created_at'])) ?></td>
                <td class="py-3 px-4 flex gap-2">
                    <!-- View Booking History -->
                    <a href="user_bookings.php?user_id=<?= $user['id'] ?>" 
                       class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs">View History</a>
                    
                    <!-- Edit -->
                    <a href="edit_user.php?id=<?= $user['id'] ?>" 
                       class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">Edit</a>
                    
                    <!-- Activate/Deactivate -->
                    <?php if ($user['status'] === 'active'): ?>
                        <a href="toggle_user.php?id=<?= $user['id'] ?>&action=deactivate" 
                           class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs"
                           onclick="return confirm('Deactivate this user?');">Deactivate</a>
                    <?php else: ?>
                        <a href="toggle_user.php?id=<?= $user['id'] ?>&action=activate" 
                           class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs"
                           onclick="return confirm('Activate this user?');">Activate</a>
                    <?php endif; ?>

                    <!-- Reset Password -->
                    <a href="reset_password.php?id=<?= $user['id'] ?>" 
                       class="px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600 text-xs"
                       onclick="return confirm('Reset password for this user?');">Reset PW</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
