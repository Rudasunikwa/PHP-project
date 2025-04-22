<?php
// Include auth functions
if (!function_exists('isLoggedIn')) {
    include_once 'auth.php';
}

// Get current page filename for active page highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Event Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Active page highlighting */
        .active-link {
            background-color: #4CAF50;
            padding: 8px 12px;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Online Event Management</h1>
        <nav>
            <ul>
                <li><a href="homepage.php" class="<?php echo ($current_page == 'homepage.php') ? 'active-link' : ''; ?>">Home</a></li>
                <li><a href="aboutus.php" class="<?php echo ($current_page == 'aboutus.php') ? 'active-link' : ''; ?>">About</a></li>
                <li><a href="contact.php" class="<?php echo ($current_page == 'contact.php') ? 'active-link' : ''; ?>">Contact</a></li>
                <li><a href="feature.php" class="<?php echo ($current_page == 'feature.php') ? 'active-link' : ''; ?>">Events</a></li>
                <?php if(isLoggedIn()): ?>
                    <li><a href="booking.php" class="<?php echo ($current_page == 'booking.php') ? 'active-link' : ''; ?>">Book Event</a></li>
                    <li><a href="my_events.php" class="<?php echo ($current_page == 'my_events.php') ? 'active-link' : ''; ?>">My Events</a></li>
                    <li><a href="userinfo.php" class="<?php echo ($current_page == 'userinfo.php') ? 'active-link' : ''; ?>">My Account</a></li>
                    <?php if(isAdmin()): ?>
                    <li><a href="admin_events.php" class="<?php echo ($current_page == 'admin_events.php') ? 'active-link' : ''; ?>" style="background-color: #ff9800; color: white; padding: 8px 12px; border-radius: 4px; font-weight: bold;">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout (<?php echo getCurrentUsername(); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="<?php echo ($current_page == 'login.php') ? 'active-link' : ''; ?>">Login</a></li>
                    <li><a href="registration.php" class="<?php echo ($current_page == 'registration.php') ? 'active-link' : ''; ?>">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
