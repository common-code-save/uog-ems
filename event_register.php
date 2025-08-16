<?php
session_start();
include 'db.php';

// Redirect to login if user not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user']['id'];

// Get event ID from query string
$eventId = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

if ($eventId <= 0) {
    die("Invalid event ID.");
}

// Fetch event info (only approved events)
$stmt = $conn->prepare("SELECT title, start_date FROM events WHERE id = ? AND status = 'approved'");
$stmt->bind_param("i", $eventId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Event not found or not available for registration.");
}

$event = $result->fetch_assoc();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if already registered
    $check = $conn->prepare("SELECT id FROM registrations WHERE user_id = ? AND event_id = ?");
    $check->bind_param("ii", $userId, $eventId);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        $message = "You are already registered for this event.";
    } else {
        // Register user
        $insert = $conn->prepare("INSERT INTO registrations (user_id, event_id, registration_date, attended) VALUES (?, ?, NOW(), 0)");
        $insert->bind_param("ii", $userId, $eventId);

        if ($insert->execute()) {
            $message = "Successfully registered for the event!";
        } else {
            $message = "Registration failed: " . htmlspecialchars($conn->error);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register for Event</title>
    <link rel="stylesheet" href="css/stele.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-form">
        <h2>Register for Event: <?= htmlspecialchars($event['title']) ?></h2>
        <p>Date: <?= htmlspecialchars($event['start_date']) ?></p>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <a href="dashboard.php" class="btn btn-secondary btn-block" style="margin-top: 10px;">Back to Dashboard</a>
        </form>
    </div>
</div>

</body>
</html>
