<?php
session_start();

// Include functions.php using absolute path
include_once 'function.php';

// Include header.php using absolute path
include 'header.php';

// Check if user is already logged in
if (isLoggedIn()) {
    header('Location:dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UoG</title>
       <link rel="stylesheet" href="css\stele.css">
</head>
<body>
    
<div class="auth-container">
    <div class="auth-form">
        <div class="auth-header">
            <h2>Create an Account</h2>
            <p>Register to access the event management system</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Registration successful! Please login.</div>
        <?php endif; ?>

        <form action="register_process.php" method="post">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="role">I am a:</label>
                <select id="role" name="role" required>
                    <option value="student">Student</option>
                    <option value="staff">Staff Member</option>
                    <option value="organizer">Event Organizer</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
        <div class="auth-footer">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>
</body>
</html>

<?php include 'footer.php'; ?>
