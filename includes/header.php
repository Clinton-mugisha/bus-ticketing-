<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UgandaMove - Public Transport E-Ticketing</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="assets/css/mobile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <span>Uganda</span>Move
            </div>
            <ul class="nav-links">
                <li><a href="#" class="active">Home</a></li>
                <li><a href="#book">Book Tickets</a></li>
                <li><a href="#routes">Routes</a></li>
                <li><a href="#track">Track Journey</a></li>
                <li><a href="#about">About</a></li>
            </ul>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="user-menu">
                        <span>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                        <a href="?logout=1" class="logout-btn">Logout</a>
                    </div>
                <?php else: ?>
                    <button class="login-btn">Login</button>
                    <button class="signup-btn">Sign Up</button>
                <?php endif; ?>
            </div>
            <div class="hamburger">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
        </nav>
    </header>