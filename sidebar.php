<?php
// Ensure session is started if not already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Default user values if not logged in (for dev/testing only)
$userName = $_SESSION['user']['name'] ?? 'Admin';
$userRole = $_SESSION['user']['role'] ?? 'admin';
?>

<div class="profile">
    <img src="image/OIP.webp" alt="Profile" />
    <h4><?php echo htmlspecialchars($userName); ?></h4>
    <p><?php echo ucfirst(htmlspecialchars($userRole)); ?></p>
</div>

<nav class="sidebar-nav">
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="calendar.php"><i class="fas fa-calendar"></i> Events</a></li>
        <li><a href="create.php"><i class="fas fa-plus"></i> Create Event</a></li>
        <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> User Management</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>
