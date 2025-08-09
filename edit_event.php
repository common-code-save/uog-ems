<?php
require "function.php";
include 'header.php';
include 'db.php';

// Check event id
if (!isset($_GET['id'])) {
    header('Location: calendar.php');
    exit;
}

$event_id = intval($_GET['id']);

// Fetch event details
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: calendar.php');
    exit;
}

$event = $result->fetch_assoc();

// Check permissions: only organizer or admin can edit
if (!isLoggedIn() || 
   ($_SESSION['user']['id'] !== $event['organizer_id'] && $_SESSION['user']['role'] !== 'admin')) {
    header('Location: calendar.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $location = sanitizeInput($_POST['location'] ?? '');
    $event_type = sanitizeInput($_POST['event_type'] ?? '');
    $capacity = isset($_POST['capacity']) ? intval($_POST['capacity']) : 0;

    // Validation
    if (empty($title)) $errors[] = "Title is required";
    if (empty($start_date)) $errors[] = "Start date is required";
    elseif (DateTime::createFromFormat('Y-m-d\TH:i', $start_date) === false) $errors[] = "Invalid start date format";
    if (!empty($end_date) && DateTime::createFromFormat('Y-m-d\TH:i', $end_date) === false) $errors[] = "Invalid end date format";
    if (empty($location)) $errors[] = "Location is required";
    if ($capacity <= 0) $errors[] = "Capacity must be a positive number";

    $allowed_types = ['academic', 'cultural', 'workshop', 'conference', 'training', 'other'];
    if (!in_array($event_type, $allowed_types)) $errors[] = "Invalid event type selected";

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, start_date = ?, end_date = ?, location = ?, event_type = ?, capacity = ? WHERE id = ?");
        if ($stmt === false) {
            $errors[] = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("ssssssii", $title, $description, $start_date, $end_date, $location, $event_type, $capacity, $event_id);
            if ($stmt->execute()) {
                header("Location: view.php?id=$event_id&updated=1");
                exit;
            } else {
                $errors[] = "Error updating event: " . $stmt->error;
            }
        }
    }
} else {
    // Set form defaults from event data
    $title = $event['title'];
    $description = $event['description'];
    $start_date = $event['start_date'];
    $end_date = $event['end_date'];
    $location = $event['location'];
    $event_type = $event['event_type'];
    $capacity = $event['capacity'];
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
    

<div class="form-container">
    <h2>Edit Event</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" novalidate>
        <div class="form-group">
            <label for="title">Event Title</label>
            <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($title); ?>">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($description); ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="start_date">Start Date & Time</label>
                <input type="datetime-local" id="start_date" name="start_date" required value="<?php echo htmlspecialchars($start_date); ?>">
            </div>

            <div class="form-group">
                <label for="end_date">End Date & Time</label>
                <input type="datetime-local" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" required value="<?php echo htmlspecialchars($location); ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="event_type">Event Type</label>
                <select id="event_type" name="event_type" required>
                    <?php
                    $types = ['academic' => 'Academic', 'cultural' => 'Cultural', 'workshop' => 'Workshop', 'conference' => 'Conference', 'training' => 'Training', 'other' => 'Other'];
                    foreach ($types as $key => $label):
                        $selected = ($event_type === $key) ? 'selected' : '';
                        echo "<option value=\"$key\" $selected>$label</option>";
                    endforeach;
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" id="capacity" name="capacity" min="1" required value="<?php echo htmlspecialchars($capacity); ?>">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Event</button>
        <a href="view.php?id=<?php echo $event_id; ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>


<?php include 'footer.php'; ?>
