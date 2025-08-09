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

// Get user ID from query
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: users.php');
    exit;
}

$userId = (int)$_GET['id'];
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic validation
    if ($name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($role, ['admin', 'organizer', 'user'])) {
        $error = 'Please fill all fields correctly.';
    } else {
        // Check if email already exists for a different user
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'Email is already used by another user.';
        } else {
            // Update user info
            if ($password !== '') {
                // Hash new password
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ?, password = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $name, $email, $role, $passwordHash, $userId);
            } else {
                // No password change
                $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
                $stmt->bind_param("sssi", $name, $email, $role, $userId);
            }

            if ($stmt->execute()) {
                $success = 'User updated successfully.';
            } else {
                $error = 'Database error: ' . $conn->error;
            }
        }
        $stmt->close();
    }
}

// Fetch user data for form
$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    // User not found
    header('Location: users.php');
    exit;
}
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit User - Admin Panel</title>
    <link rel="stylesheet" href="css/stele.css" />
    <style>
    /* Style the labels */
form label {
    display: inline-block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #333;
    font-size: 14px;
}

/* Style all input fields and select */
form input[type="text"],
form input[type="email"],
form input[type="password"],
form select {
    width: 40%;
    padding: 10px 12px;
    margin-bottom: 20px;
    border: 1px solid #000;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s, box-shadow 0.3s;
    background-color: #fff;
    color: #333;
    box-sizing: border-box;
}

/* OPTIONAL: Special styling for password input */
form input[type="password"] {
    margin-left: 50px; /* You can adjust this or remove if not needed */
}

/* Input focus effect */
form input:focus,
form select:focus {
    border-color: #007BFF;
    outline: none;
    box-shadow: 0 0 4px rgba(0, 123, 255, 0.4);
}

/* Hover effect */
form input:hover,
form select:hover {
    border-color: #999;
}
   </style>
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
                <li><a href="users.php"><i class="fas fa-users"></i> User Management</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <h2>Edit User</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form   method="POST" class="form-edit-user">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required /> <br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required /> <br>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>User</option>
                <option value="organizer" <?php if ($user['role'] === 'organizer') echo 'selected'; ?>>Organizer</option>
                <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
            </select><br>

            <label for="password">New Password (leave blank to keep current):</label><br>
            <input type="password" id="password" name="password" /><br>

            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="users.php" class="btn btn-secondary">Cancel</a>
        </form>
    </main>
</div>
</body>
</html>

<?php include 'footer.php'; ?>
