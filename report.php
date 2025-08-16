<?php 
requireAdmin();
include 'header.php';
include 'db.php';

// Get filter values from GET
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$eventType = $_GET['event_type'] ?? '';

// Build WHERE clauses for filters
$whereClauses = [];

if ($startDate) {
    $startDateEscaped = $conn->real_escape_string($startDate);
    $whereClauses[] = "start_date >= '$startDateEscaped'";
}
if ($endDate) {
    $endDateEscaped = $conn->real_escape_string($endDate);
    $whereClauses[] = "start_date <= '$endDateEscaped'";
}
if ($eventType) {
    $eventTypeEscaped = $conn->real_escape_string($eventType);
    $whereClauses[] = "event_type = '$eventTypeEscaped'";
}

$whereSql = '';
if (!empty($whereClauses)) {
    $whereSql = ' WHERE ' . implode(' AND ', $whereClauses);
}

// Total Events respecting filters
$totalEventsQuery = "SELECT COUNT(*) as total FROM events $whereSql";
$result = $conn->query($totalEventsQuery);
$totalEvents = $result->fetch_assoc()['total'] ?? 0;

// Total Participants respecting filtered events
$totalParticipantsQuery = "
    SELECT COUNT(*) as total FROM registrations r
    JOIN events e ON r.event_id = e.id
    $whereSql
";
$result = $conn->query($totalParticipantsQuery);
$totalParticipants = $result->fetch_assoc()['total'] ?? 0;

// Attendance rate respecting filtered events
$attendedQuery = "
    SELECT COUNT(*) as attended FROM registrations r
    JOIN events e ON r.event_id = e.id
    $whereSql AND r.attended = 1
";

$totalRegQuery = "
    SELECT COUNT(*) as total FROM registrations r
    JOIN events e ON r.event_id = e.id
    $whereSql
";

$result = $conn->query($attendedQuery);
$attended = $result->fetch_assoc()['attended'] ?? 0;

$result = $conn->query($totalRegQuery);
$totalReg = $result->fetch_assoc()['total'] ?? 0;

$attendanceRate = $totalReg > 0 ? round(($attended / $totalReg) * 100) : 0;

?>

<div class="dashboard">
    <aside class="sidebar">
        <?php include 'sidebar.php'; ?>
    </aside>
    
    <main class="main-content">
        <h2>Event Reports and Analytics</h2>
        
        <div class="report-filters">
            <form method="get" action="">
                <div class="filter-group">
                    <label for="start_date">From:</label>
                    <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($startDate) ?>">
                </div>
                <div class="filter-group">
                    <label for="end_date">To:</label>
                    <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($endDate) ?>">
                </div>
                <div class="filter-group">
                    <label for="event_type">Event Type:</label>
                    <select id="event_type" name="event_type">
                        <option value="" <?= $eventType === '' ? 'selected' : '' ?>>All Types</option>
                        <option value="academic" <?= $eventType === 'academic' ? 'selected' : '' ?>>Academic</option>
                        <option value="cultural" <?= $eventType === 'cultural' ? 'selected' : '' ?>>Cultural</option>
                        <option value="workshop" <?= $eventType === 'workshop' ? 'selected' : '' ?>>Workshop</option>
                        <option value="conference" <?= $eventType === 'conference' ? 'selected' : '' ?>>Conference</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </form>
        </div>
        
        <div class="report-cards">
            <div class="report-card">
                <h3>Total Events</h3>
                <p><?= $totalEvents ?></p>
            </div>
            
            <div class="report-card">
                <h3>Total Participants</h3>
                <p><?= $totalParticipants ?></p>
            </div>
            
            <div class="report-card">
                <h3>Attendance Rate</h3>
                <p><?= $attendanceRate ?>%</p>
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
                    $registrationsQuery = "
                        SELECT r.id, r.registration_date, r.attended, 
                               e.title as event_title, 
                               u.name as user_name 
                        FROM registrations r
                        JOIN events e ON r.event_id = e.id
                        JOIN users u ON r.user_id = u.id
                        $whereSql
                        ORDER BY r.registration_date DESC
                        LIMIT 10
                    ";

                    $result = $conn->query($registrationsQuery);

                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>'.htmlspecialchars($row['event_title']).'</td>';
                            echo '<td>'.htmlspecialchars($row['user_name']).'</td>';
                            echo '<td>'.htmlspecialchars($row['registration_date']).'</td>';
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
