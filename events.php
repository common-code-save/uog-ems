<?php
require 'db.php';

$type = isset($_GET['type']) ? $_GET['type'] : null;
$time = isset($_GET['time']) ? $_GET['time'] : 'upcoming';

$query = "SELECT e.id, e.title, e.start_date as start, e.end_date as end, 
          e.location, e.event_type, e.status 
          FROM events e 
          WHERE 1=1";

if ($type) {
    $query .= " AND e.event_type = '" . $conn->real_escape_string($type) . "'";
}

switch ($time) {
    case 'upcoming':
        $query .= " AND e.start_date >= NOW()";
        break;
    case 'past':
        $query .= " AND e.start_date < NOW()";
        break;
    // 'all' doesn't need any additional conditions
}

$query .= " ORDER BY e.start_date ASC";

$result = $conn->query($query);
$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $event = [
            'id' => $row['id'],
            'title' => $row['title'],
            'start' => $row['start'],
            'end' => $row['end'],
            'location' => $row['location'],
            'extendedProps' => [
                'type' => $row['event_type'],
                'status' => $row['status']
            ]
        ];
        
        // Add color based on event type
        switch ($row['event_type']) {
            case 'academic':
                $event['color'] = '#0c5460';
                $event['textColor'] = '#ffffff';
                break;
            case 'cultural':
                $event['color'] = '#155724';
                $event['textColor'] = '#ffffff';
                break;
            case 'workshop':
                $event['color'] = '#856404';
                $event['textColor'] = '#ffffff';
                break;
            case 'conference':
                $event['color'] = '#383d41';
                $event['textColor'] = '#ffffff';
                break;
            default:
                $event['color'] = '#003366';
                $event['textColor'] = '#ffffff';
        }
        
        $events[] = $event;
    }
}

echo json_encode($events);
$conn->close();
?>