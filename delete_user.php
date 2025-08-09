<?php
require 'function.php';
requireAuth();

$userRole = $_SESSION['user']['role'];
if ($userRole !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: users.php');
    exit;
}

$userId = (int)$_GET['id'];

// Prevent admin from deleting themselves
if ($userId === $_SESSION['user']['id']) {
    // Optionally set a flash message or alert before redirect
    header('Location: users.php');
    exit;
}

// Delete user
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->close();

header('Location: users.php');
exit;
