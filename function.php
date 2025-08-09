<?php
// Common functions for the EMS

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']);
}

// Check if user has specific role
function hasRole($role) {
    return isLoggedIn() && $_SESSION['user']['role'] === $role;
}

// Redirect to login if not authenticated
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Redirect to login if not admin
function requireAdmin() {
    requireAuth();
    if (!hasRole('admin')) {
        header('Location: login.php');
        exit;
    }
}

// Format date for display
function formatDate($dateString) {
    return date('F j, Y', strtotime($dateString));
}

// Format datetime for display
function formatDateTime($datetimeString) {
    return date('F j, Y g:i A', strtotime($datetimeString));
}
?>
