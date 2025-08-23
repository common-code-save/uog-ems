<?php
session_start();
require 'db.php';
require 'function.php';
requireAuth();  // Make sure user is logged in

header('Content-Type: application/json'); // for debugging AJAX

// Debug: log POST and session data
file_put_contents('debug_register.txt', 
    "POST: " . print_r($_POST, true) . "\n" .
    "SESSION user: " . print_r($_SESSION['user'] ?? 'No user', true) . "\n",
    FILE_APPEND
);

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in.']);
    exit;
}

$userId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = intval($_POST['event_id']);

    try {
        $stmt = $conn->prepare("INSERT INTO registrations (event_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $eventId, $userId);
        $stmt->execute();

        $_SESSION['message'] = "Successfully registered for the event.";

        echo json_encode(['status' => 'success', 'message' => $_SESSION['message']]);
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {  // Duplicate entry error code
            $_SESSION['message'] = "You have already registered for this event.";
            echo json_encode(['status' => 'error', 'message' => $_SESSION['message']]);
        } else {
            $_SESSION['message'] = "An error occurred: " . $e->getMessage();
            echo json_encode(['status' => 'error', 'message' => $_SESSION['message']]);
        }
    }

    $conn->close();
} else {
    $_SESSION['message'] = "Invalid request.";
    echo json_encode(['status' => 'error', 'message' => $_SESSION['message']]);
}
exit;
