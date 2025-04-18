<?php
// Include configuration
require_once 'config/config.php';

// Include data
require_once 'data/routes.php';

// Include handlers (processing logic) - ensure correct path
require_once 'handlers/auth_handler.php';
require_once 'handlers/booking_handler.php';
require_once 'handlers/tracking_handler.php';

// Display any SQL errors during development (remove in production)
if (isset($conn) && $conn->error) {
    echo '<div style="color:red;padding:10px;background:#ffeeee;margin:10px;border:1px solid #ff0000;">';
    echo 'SQL Error: ' . htmlspecialchars($conn->error);
    echo '</div>';
}

// Include header (HTML head and navigation)
include_once 'includes/header.php';

// Include hero section
include_once 'includes/hero.php';

// Include booking section
include_once 'includes/booking.php';

// Include routes and tracking section
include_once 'includes/routes_tracking.php';

// Include about and app download section
include_once 'includes/about.php';

// Include modals (login, signup, payment, etc.)
include_once 'includes/modals.php';

// Include footer
include_once 'includes/footer.php';
?>