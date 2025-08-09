<?php 
requireAdmin();
include 'header.php';
include 'db.php';
?>

<div class="dashboard">
    <aside class="sidebar">
        <!-- Same sidebar as dashboard.php -->
        <?php include 'sidebar.php'; ?>
    </aside>
    
    <main class="main-content">
        <h2>Event Reports and Analytics</h2>
        
        <div class="report-filters">
            <form method="get" action="">
                <div class="filter-group">
                    <label for="start_date">From:</label>
                    <input type="date" id="start_date" name="start_date">
                </div>
                <div class="filter-group">
                    <label for="end_date">To:</label>
                    <input type="date" id="end_date" name="end_date">
                </div>
                <div class="filter-group">
                    <label for="event_type">Event Type:</label>
                    <select id="event_type" name="event_type">
                        <option value="">All Types</option>
                        <option value="academic">Academic</option>
                        <option value="cultural">Cultural</option>
                        <option value="workshop">Workshop</option>
                        <option value="conference">Conference</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </form>
        </div>
        
        <div class="report-cards">
            <div class="report-card">
                <h3>Total Events</h3>
                <?php
                $totalEventsQuery = "SELECT COUNT(*) as total FROM events";
                $result = $conn->query($totalEventsQuery);
                $total = $result->fetch_assoc()['total'];
                ?>
                <p><?php echo $total; ?></p>
            </div>
            
            <div class="report-card">
                <h3>Total Participants</h3>
                <?php
                $totalParticipantsQuery = "SELECT COUNT(*) as total FROM registrations";
                $result = $conn->query($totalParticipantsQuery);
                $total = $result->fetch_assoc()['total'];
                ?>
                <p><?php echo $total; ?></p>
            </div>
            
            <div class="report-card">
                <h3>Attendance Rate</h3>
                <?php
                $attendedQuery = "SELECT COUNT(*) as attended FROM registrations WHERE attended = 1";
                $totalRegQuery = "SELECT COUNT(*) as total FROM registrations";
                
                $result = $conn->query($attendedQuery);
                $attended = $result->fetch_assoc()['attended'];
                
                $result = $conn->query($totalRegQuery);
                $total = $result->fetch_assoc()['total'];
                
                $rate = $total > 0 ? round(($attended / $total) * 100) : 0;
                ?>
                <p><?php echo $rate; ?>%</p>
            </div>
        </div>
        
        <div class="chart-container">
            <canvas id="eventsChart"></canvas>
        </div>
        
        <div class="table-responsive">
            <h3>Recent Registrations</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Participant</th>
                        <th>Registration Date</th>
                        <th>Attended</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $registrationsQuery = "SELECT r.id, r.registration_date, r.attended, 
                                         e.title as event_title, 
                                         u.name as user_name 
                                         FROM registrations r
                                         JOIN events e ON r.event_id = e.id
                                         JOIN users u ON r.user_id = u.id
                                         ORDER BY r.registration_date DESC
                                         LIMIT 10";
                    $result = $conn->query($registrationsQuery);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>'.htmlspecialchars($row['event_title']).'</td>';
                            echo '<td>'.htmlspecialchars($row['user_name']).'</td>';
                            echo '<td>'.formatDateTime($row['registration_date']).'</td>';
                            echo '<td>'.($row['attended'] ? 'Yes' : 'No').'</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4">No registrations found</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample chart data - in a real app, you would fetch this via AJAX
    const ctx = document.getElementById('eventsChart').getContext('2d');
    const eventsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Events Created',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: 'rgba(0, 51, 102, 0.7)',
                borderColor: 'rgba(0, 51, 102, 1)',
                borderWidth: 1
            }, {
                label: 'Participants',
                data: [120, 190, 30, 50, 20, 30],
                backgroundColor: 'rgba(230, 166, 18, 0.7)',
                borderColor: 'rgba(230, 166, 18, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

<?php include 'footer.php'; ?>