<?php 
session_start();
include 'db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> University of Gondar Event Management System</title>

    <!-- ✅ CSS Link -->
     <!-- Update path if needed -->

    <!-- Optional: Font Awesome for icons -->
    <link rel="stylesheet" href="css\stele.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="hero" style="align-item:center">
    <h1> welcome University of Gondar Event Management System</h1>
    <p>Streamlining university events from planning to reporting</p>
    <div class="hero-buttons">
        <a href="login.php" class="btn btn-primary">Login</a>
        <a href="firstlogin.php" class="btn btn-secondary">View Events</a>
    </div>
</div>

<div class="features">
    <div class="feature">
        <i class="fas fa-calendar-plus"></i>
        <h3>Event Creation</h3>
        <p>Easily create and manage university events</p>
    </div>
    <div class="feature">
        <i class="fas fa-user-plus"></i>
        <h3>Online Registration</h3>
        <p>Students and staff can register for events online</p>
    </div>
    <div class="feature">
        <i class="fas fa-chart-bar"></i>
        <h3>Analytics Dashboard</h3>
        <p>Track participation and gather feedback</p>
    </div>
</div>

</body>
</html>
<?php include 'footer.php'; ?>

