
<?php/*What is urlencode() in PHP?

A function that converts a string into a URL-safe format.

Replaces spaces and special characters with + or %XX codes so it can safely go in a URL and, 
Use in a URL (GET parameter)*/?>
<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Allowed roles for public registration (no admin allowed here)
    $allowedRoles = ['student', 'staff', 'organizer'];

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        header('Location: register.php?error=' . urlencode('All fields are required.'));
        exit;
    }

    // Name validation
    if (!preg_match("/^[a-zA-Z\s]*$/", $name)) {
        header('Location: register.php?error=' . urlencode('Name can only contain letters and spaces.'));
        exit;
    } else{
        
         $name = ucwords(strtolower($name));
    }
    if (strlen($name) < 3 || strlen($name) > 30) {
        header('Location: register.php?error=' . urlencode('Name must be between 3 and 30 characters.'));
        exit;
    }

    // Role validation
    if (!in_array($role, $allowedRoles)) {
        header('Location: register.php?error=' . urlencode('Invalid role selected.'));
        exit;
    }

    // Password validation
    if ($password !== $confirm_password) {
        header('Location: register.php?error=' . urlencode('Passwords do not match.'));
        exit;
    }
    if (strlen($password) < 5) {
        header('Location: register.php?error=' . urlencode('Password must be at least 5 characters.'));
        exit;
    }

    // OPTIONAL: Strong password rule (uncomment if you want)
    /*
    if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{5,}$/", $password)) {
        header('Location: register.php?error=' . urlencode('Password must contain uppercase, lowercase, number, and special character.'));
        exit;
    }
    */

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        header('Location: register.php?error=' . urlencode('Email already registered.'));
        exit;
    }
    $stmt->close();

    // Hash password and insert user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

    if ($stmt->execute()) {
        $stmt->close();
        header('Location: register.php?success=1');
        exit;
    } else {
        $error = "Database error: " . $stmt->error;
        $stmt->close();
        header('Location: register.php?error=' . urlencode($error));
        exit;
    }
} else {
    header('Location: register.php');
    exit;
}
