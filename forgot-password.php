<?php
session_start();
include 'header.php';
include 'db.php';
include 'function.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            // In real app: send email with token
            // For now, just simulate
            $message = "A password reset link has been sent to your email address.";
        } else {
            $message = "No account found with that email.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - EMS</title>
    <link rel="stylesheet" href="css/stele.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-form">
        <div class="auth-header">
            <h2>Forgot Password</h2>
            <p>Enter your registered email to reset your password.</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="forgot-password.php" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
        </form>

        <div class="auth-footer">
            <p><a href="login.php">Back to Login</a></p>
        </div>
    </div>
</div>

</body>
</html>

<?php include 'footer.php'; ?>
