<?php 
require "function.php";
include 'header.php';
include 'db.php';

if (!isset($_GET['id'])) {
    header('Location:calendar.php');
    exit;
}

$event_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT e.*, u.name as organizer_name 
                       FROM events e 
                       JOIN users u ON e.organizer_id = u.id 
                       WHERE e.id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: /events/calendar.php');
    exit;
}

$event = $result->fetch_assoc();

// Check if user is registered for this event
$isRegistered = false;
$attending = false;
if (isLoggedIn()) {
    $user_id = $_SESSION['user']['id'];
    $checkReg = $conn->prepare("SELECT attended FROM registrations 
                               WHERE event_id = ? AND user_id = ?");
    $checkReg->bind_param("ii", $event_id, $user_id);
    $checkReg->execute();
    $regResult = $checkReg->get_result();
    
    if ($regResult->num_rows > 0) {
        $isRegistered = true;
        $registration = $regResult->fetch_assoc();
        $attending = $registration['attended'];
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
    

<div class="event-view">
    <div class="event-header">
        <h2><?php echo htmlspecialchars($event['title']); ?></h2>
        <div class="event-meta">
            <span class="event-type <?php echo $event['event_type']; ?>">
                <?php echo ucfirst($event['event_type']); ?>
            </span>
            <span class="event-status <?php echo $event['status']; ?>">
                <?php echo ucfirst($event['status']); ?>
            </span>
        </div>
    </div>
    
    <div class="event-details">
        <div class="event-info">
            <div class="info-item">
                <i class="fas fa-calendar-day"></i>
                <div>
                    <h4>Date & Time</h4>
                    <p>
                        <?php echo formatDateTime($event['start_date']); ?>
                        <?php if (!empty($event['end_date'])): ?>
                            to <?php echo formatDateTime($event['end_date']); ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h4>Location</h4>
                    <p><?php echo htmlspecialchars($event['location']); ?></p>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fas fa-user-tie"></i>
                <div>
                    <h4>Organizer</h4>
                    <p><?php echo htmlspecialchars($event['organizer_name']); ?></p>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fas fa-users"></i>
                <div>
                    <h4>Capacity</h4>
                    <p><?php echo $event['capacity']; ?> attendees</p>
                </div>
            </div>
        </div>
        
        <div class="event-description">
            <h3>About This Event</h3>
            <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
        </div>
    </div>
    
    <div class="event-actions">
        <?php if (isLoggedIn()): ?>
            <?php if ($isRegistered): ?>
                <?php if ($attending): ?>
                    <span class="badge success">You're attending this event</span>
                <?php else: ?>
                    <span class="badge">You're registered for this event</span>
                <?php endif; ?>
                
                <?php if (strtotime($event['start_date']) > time()): ?>
                    <a href="unregister.php?event_id=<?php echo $event_id; ?>" class="btn btn-danger">Cancel Registration</a>
                <?php endif; ?>
            <?php else: ?>
                <?php if (strtotime($event['start_date']) > time() && $event['status'] === 'approved'): ?>
                    <a href="register.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">Register for Event</a>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <p>Please <a href="login.php">login</a> to register for this event</p>
        <?php endif; ?>
        
        <?php if (isLoggedIn() && ($_SESSION['user']['id'] === $event['organizer_id'] || $_SESSION['user']['role'] === 'admin')): ?>
            <a href="edit_event.php?id=<?php echo $event_id; ?>" class="btn btn-secondary">Edit Event</a>
        <?php endif; ?>
    </div>
    
    <?php if (isset($_GET['created'])): ?>
        <div class="alert alert-success">
            Event created successfully!
        </div>
    <?php endif; ?>
</div>
</body>
</html>


<?php include 'footer.php'; ?>