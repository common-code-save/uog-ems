<?php 
require 'function.php';
requireAuth();
include 'header.php';
include 'db.php';

$userRole = $_SESSION['user']['role'];
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
    
<div class="dashboard">
    <aside class="sidebar">
        <div class="profile">
            <img src="image/OIP.webp" alt="Profile">
            <h4><?php echo htmlspecialchars($_SESSION['user']['name']); ?></h4>
            <p><?php echo ucfirst($userRole); ?></p>
        </div>
        
        <nav class="sidebar-nav">
            <ul>
            <li class="active"><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="events_calendar.php"><i class="fas fa-calendar"></i> Events</a></li>
                <?php if($userRole === 'admin' || $userRole === 'organizer'): ?>
                    <li><a href="create_event.php"><i class="fas fa-plus"></i> Create Event</a></li>
                <?php endif; ?>
                <?php if($userRole === 'admin'): ?>
                    <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                    <li><a href="users.php"><i class="fas fa-users"></i> User Management</a></li>
                <?php endif; ?>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="main-content">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h2>
        <p class="subtitle">Here's what's happening with your events</p>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Upcoming Events</h3>
                <?php
                $upcomingQuery = "SELECT COUNT(*) as count FROM events WHERE start_date >= NOW()";
                $result = $conn->query($upcomingQuery);
                $count = $result->fetch_assoc()['count'];
                ?>
                <p><?php echo $count; ?></p>
            </div>
            
            <div class="stat-card">
                <h3>Your Registrations</h3>
                <?php
                $registrationsQuery = "SELECT COUNT(*) as count FROM registrations WHERE user_id = ?";
                $stmt = $conn->prepare($registrationsQuery);
                $stmt->bind_param("i", $_SESSION['user']['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $count = $result->fetch_assoc()['count'];
                ?>
                <p><?php echo $count; ?></p>
            </div>
            
            <?php if($userRole === 'admin' || $userRole === 'organizer'): ?>
                <div class="stat-card">
                    <h3>Your Events</h3>
                    <?php
                    $yourEventsQuery = "SELECT COUNT(*) as count FROM events WHERE organizer_id = ?";
                    $stmt = $conn->prepare($yourEventsQuery);
                    $stmt->bind_param("i", $_SESSION['user']['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $count = $result->fetch_assoc()['count'];
                    ?>
                    <p><?php echo $count; ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="dashboard-sections">
            <section class="recent-events">
                <h3>Recent Events</h3>
                <div class="event-list">
                    <?php
                    $eventsQuery = "SELECT e.id, e.title, e.start_date, e.location 
                                  FROM events e 
                                  ORDER BY e.start_date DESC 
                                  LIMIT 5";
                    $result = $conn->query($eventsQuery);
                    
                    if ($result->num_rows > 0) {
                        while($event = $result->fetch_assoc()) {
                            echo '<div class="event-item">';
                            echo '<h4><a href="view.php?id='.$event['id'].'">'.$event['title'].'</a></h4>';
                            echo '<p><i class="fas fa-calendar-day"></i> '.formatDate($event['start_date']).'</p>';
                            echo '<p><i class="fas fa-map-marker-alt"></i> '.htmlspecialchars($event['location']).'</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No recent events found.</p>';
                    }
                    ?>
                </div>
                <a href="events_calendar.php" class="btn btn-secondary" style="color:blue">View All Events</a>
            </section>
            
            <?php if($userRole === 'admin' || $userRole === 'organizer'): ?>
                <section class="your-events">
                    <h3>Your Organized Events</h3>
                    <div class="event-list">
                        <?php
                        $yourEventsQuery = "SELECT e.id, e.title, e.start_date, e.status 
                                          FROM events e 
                                          WHERE e.organizer_id = ? 
                                          ORDER BY e.start_date DESC 
                                          LIMIT 5";
                        $stmt = $conn->prepare($yourEventsQuery);
                        $stmt->bind_param("i", $_SESSION['user']['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0) {
                            while($event = $result->fetch_assoc()) {
                                echo '<div class="event-item">';
                                echo '<h4><a href="/events/view.php?id='.$event['id'].'">'.$event['title'].'</a></h4>';
                                echo '<p><i class="fas fa-calendar-day"></i> '.formatDate($event['start_date']).'</p>';
                                echo '<span class="status-badge '.$event['status'].'">'.ucfirst($event['status']).'</span>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>You have not organized any events yet.</p>';
                        }
                        ?>
                    </div>
                    <a href="create_event.php" class="btn btn-primary">Create New Event</a>
                </section>
            <?php endif; ?>
        </div>
    </main>
</div>

</body>
</html>

<?php include 'footer.php'; ?>