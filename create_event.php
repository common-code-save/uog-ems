<?php 
require "function.php";
requireAuth();
include 'header.php';
include 'db.php';

// Only organizers and admins can create events
if (!in_array($_SESSION['user']['role'], ['admin', 'organizer'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs - assume sanitizeInput is defined elsewhere
    $title = sanitizeInput($_POST['title'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $location = sanitizeInput($_POST['location'] ?? '');
    $event_type = sanitizeInput($_POST['event_type'] ?? '');
    $capacity = isset($_POST['capacity']) ? intval($_POST['capacity']) : 0;
    $organizer_id = $_SESSION['user']['id'];
    
    // Basic validation
    $errors = [];

    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($start_date)) {
        $errors[] = "Start date is required";
    } elseif (DateTime::createFromFormat('Y-m-d\TH:i', $start_date) === false) {
        $errors[] = "Start date format is invalid";
    }
    if (!empty($end_date) && DateTime::createFromFormat('Y-m-d\TH:i', $end_date) === false) {
        $errors[] = "End date format is invalid";
    }
    if (empty($location)) {
        $errors[] = "Location is required";
    }
    if ($capacity <= 0) {
        $errors[] = "Capacity must be a positive number";
    }
    
    // Optional: Validate event_type is one of allowed values
    $allowed_types = ['academic', 'cultural', 'workshop', 'conference', 'training', 'other'];
    if (!in_array($event_type, $allowed_types)) {
        $errors[] = "Invalid event type selected";
    }
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO events 
            (title, description, start_date, end_date, location, event_type, capacity, organizer_id, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        if ($stmt === false) {
            $errors[] = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param(
                "ssssssii", 
                $title, 
                $description, 
                $start_date, 
                $end_date, 
                $location, 
                $event_type, 
                $capacity, 
                $organizer_id
            );
            
            if ($stmt->execute()) {
                $event_id = $stmt->insert_id;
                header("Location: view.php?id=$event_id&created=1");
                exit;
            } else {
                $errors[] = "Error creating event: " . $stmt->error;
            }
        }
    }
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
    <h2>Create New Event</h2>
    
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
            <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="start_date">Start Date & Time</label>
                <input type="datetime-local" id="start_date" name="start_date" required value="<?php echo htmlspecialchars($_POST['start_date'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="end_date">End Date & Time</label>
                <input type="datetime-local" id="end_date" name="end_date" value="<?php echo htmlspecialchars($_POST['end_date'] ?? ''); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" required value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>">
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="event_type">Event Type</label>
                <select id="event_type" name="event_type" required>
                    <?php 
                    $types = ['academic' => 'Academic', 'cultural' => 'Cultural', 'workshop' => 'Workshop', 'conference' => 'Conference', 'training' => 'Training', 'other' => 'Other'];
                    $selected_type = $_POST['event_type'] ?? '';
                    foreach ($types as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($selected_type === $key) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" id="capacity" name="capacity" min="1" required value="<?php echo htmlspecialchars($_POST['capacity'] ?? '50'); ?>">
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Create Event</button>
        <a href="events_calendar.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>


<?php include 'footer.php'; ?>
