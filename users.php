<?php
require 'function.php';
requireAuth();

$userRole = $_SESSION['user']['role'];
if ($userRole !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

include 'header.php';
include 'db.php';

// Fetch all users
$usersQuery = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($usersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Management - Admin Panel</title>
    <link rel="stylesheet" href="css/stele.css" />
</head>
<body>
<div class="dashboard">
    <aside class="sidebar">
        <div class="profile">
            <img src="image/OIP.webp" alt="Profile" />
            <h4><?php echo htmlspecialchars($_SESSION['user']['name']); ?></h4>
            <p><?php echo ucfirst($userRole); ?></p>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="calendar.php"><i class="fas fa-calendar"></i> Events</a></li>
                <li><a href="create.php"><i class="fas fa-plus"></i> Create Event</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li class="active"><a href="users.php"><i class="fas fa-users"></i> User Management</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <h2>User Management</h2>
        <p class="subtitle">Manage all registered users.</p>

        <?php if ($result->num_rows > 0): ?>
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo ucfirst($user['role']); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </main>
</div>
</body>
</html>

<?php include 'footer.php'; ?>
