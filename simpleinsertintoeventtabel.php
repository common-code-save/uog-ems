<?php
include "db.php";

$sql = "INSERT INTO events (title, description, start_date, end_date, location, event_type, capacity, organizer_id, status)
VALUES 
(
  'Academic Orientation', 
  'Orientation session for new students', 
  '2025-09-01 09:00:00', 
  '2025-09-01 12:00:00', 
  'Main Auditorium', 
  'academic', 
  200, 
  1, 
  'approved'
),
(
  'Cultural Fest 2025', 
  'An evening of music, dance, and food', 
  '2025-10-15 18:00:00', 
  '2025-10-15 22:00:00', 
  'Outdoor Grounds', 
  'cultural', 
  500, 
  2, 
  'approved'
),
(
  'Web Development Workshop', 
  'Hands-on workshop on full-stack web development', 
  '2025-08-20 10:00:00', 
  '2025-08-20 16:00:00', 
  'Lab 3A', 
  'workshop', 
  30, 
  1, 
  'approved'
),
(
  'AI Conference 2025', 
  'Annual conference on AI trends and research', 
  '2025-11-05 09:00:00', 
  '2025-11-07 17:00:00', 
  'Convention Center', 
  'conference', 
  1000, 
  2, 
  'pending'
),
(
  'Faculty Training Program', 
  'Training program for newly recruited faculty members', 
  '2025-08-25 08:30:00', 
  '2025-08-25 16:00:00', 
  'Training Room 2B', 
  'training', 
  50, 
  1, 
  'approved'
),
(
  'Miscellaneous Meetup', 
  'Open networking session for students and faculty', 
  '2025-09-10 14:00:00', 
  '2025-09-10 16:00:00', 
  'Café Lounge', 
  'other', 
  100, 
  2, 
  'cancelled'
);";  // <-- moved the semicolon outside the string

$res = $conn->query($sql);

if ($res) {
    echo "Successfully inserted";
} else {
    echo "Error: " . $conn->error;
}
?>
