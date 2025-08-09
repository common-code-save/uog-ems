<?php 
session_start(); 
include 'db.php';  // your database connection

// Fetch events from the database (example query)
$events = [];
$sql = "SELECT * FROM events ORDER BY created_at ASC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Events Calendar - University of Gondar</title>
    <link rel="stylesheet" href="css/stele.css" />
    <!-- Optional: Include a calendar library like FullCalendar or just simple styling -->
    <style>
        .calendar {

            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        
        }
        .event {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .event:last-child {
            border-bottom: none;
        }
        .event-date {
            font-weight: bold;
            color: #333;
        }
        .event-title {
            font-size: 1.2em;
            margin: 5px 0;
        }
        .event-description {
            color: #555;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="calendar">
    <h2>University of Gondar Events Calendar</h2>
    <?php if (count($events) === 0): ?>
        <p>No upcoming events found.</p>
    <?php else: ?>
        <?php foreach ($events as $event): ?>
            <div class="event">
                <div class="event-date">
                    <?php 
                    // Safely print date if start_date exists and valid
                    if (!empty($event['start_date'])) {
                        echo date('F j, Y', strtotime($event['start_date']));
                    } else {
                        echo "Date not available";
                    }
                    ?>
                </div>
                <div class="event-title"><?php echo htmlspecialchars($event['title']); ?></div>
                <div class="event-description"><?php echo nl2br(htmlspecialchars($event['description'])); ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
