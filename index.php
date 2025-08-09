<?php 
session_start();
include 'db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>University of Gondar Event Management System</title>

    <!-- Font Awesome for icons (CDN link) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          integrity="sha512-papbRLFdaA5F3HLN8AlRRg9DJ9TfxKk/1ZMb3Y9Pl0EKMBkM8CMzOMg7PcSzT3jNSMDtUl2CdpKXh8u6lQ7+Eg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Your external CSS -->
    <link rel="stylesheet" href="css/stele.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="hero" style="align-items:center; text-align:center; padding: 60px 20px;">
    <h1>Welcome to the University of Gondar Event Management System</h1>
    <p class="hero-subtitle">Streamlining university events from planning to reporting with ease and efficiency.</p>

    <p class="hero-description">
        Whether you are a student, staff, or organizer, our system helps you stay connected with all upcoming events on campus.
        Create events, register online, and track event analytics all in one place. Join us in making the University of Gondar a vibrant community with engaging and well-organized events.
    </p>

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

<?php include 'footer.php'; ?>

</body>
</html>
