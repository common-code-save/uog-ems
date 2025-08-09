<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UoG EMS - University of Gondar Event Management System</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Your full CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header>
    <a href="index.php" class="logo-link">
        <h1>UoG <span>EMS</span></h1>
    </a>

    <!-- Desktop Navigation -->
    <nav class="desktop-nav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="events.php">Events</a></li>
            <?php if(isset($_SESSION['user'])): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Mobile Menu Button -->
    <button id="mobile-menu-btn" class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
    </button>
</header>

<!-- Mobile Navigation Menu -->
<nav id="mobile-menu" class="mobile-menu hidden">
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="events.php">Events</a></li>
        <?php if(isset($_SESSION['user'])): ?>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>
    <script src="js/script.js"></script>
        </body>
        </html>
