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

    if (!in_array($role, $allowedRoles)) {
        header('Location: register.php?error=' . urlencode('Invalid role selected.'));
        exit;
    }

    if ($password !== $confirm_password) {
        header('Location: register.php?error=' . urlencode('Passwords do not match.'));
        exit;
    }

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
