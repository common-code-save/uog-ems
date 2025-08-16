
<?php
include "db.php";


// Handle form submission to add new event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = $_POST['start_date'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    $sql = "INSERT INTO events (title, description, start_date, location) 
            VALUES ('$title', '$description', '$start_date', '$location')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>Event added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}

// Handle delete event
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM events WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>Event deleted successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error deleting event.</p>";
    }
}

// Fetch events (ordering by correct column name)
$result = mysqli_query($conn, "SELECT * FROM events ORDER BY start_date ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>College Events Management</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>College Event Management System</h2>

<h3>Add New Event</h3>
<form method="POST" action="">
    <label>Title:</label>
    <input type="text" name="title" required>
    
    <label>Description:</label>
    <textarea name="description" rows="3" required></textarea>

    <label>Date:</label>
    <input type="date" name="start_date" required>

    <label>Location:</label>
    <input type="text" name="location" required>

    <input type="submit" name="add_event" value="Add Event">
</form>

<h3>Upcoming Events</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Date</th>
        <th>Location</th>
        <th>Action</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td><?= $row['start_date'] ?></td>
        <td><?= htmlspecialchars($row['location']) ?></td>
        <td>
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>

<?php
mysqli_close($conn);
?>
