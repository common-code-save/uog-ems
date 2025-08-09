<?php 
session_start();
include 'header.php';
include 'db.php';

include 'function.php';
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css\stele.css">
</head>
<body>
    

<div class="auth-container">
    <div class="auth-form">
        <div class="auth-header">
            <h2>Login to EMS</h2>
            <p>Enter your credentials to access the system</p>
        </div>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <form action="authenticate.php" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <small><a href="forgot-password.php">Forgot password?</a></small>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <div class="auth-footer">
            <p style="color:black">Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</div>
</body>
</html>

<?php include 'footer.php'; ?>