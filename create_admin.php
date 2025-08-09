<?php
require 'db.php';

$name = "Admin User";
$email = "admin@example.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);  // Securely hash password
$role = "admin";

$stmt = $conn->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("ssss", $name, $email, $password, $role);

if ($stmt->execute()) {
    echo "Admin user created successfully!";
} else {
    echo " Error: " . $conn->error;
}
