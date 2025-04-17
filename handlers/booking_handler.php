<?php
// Handle ticket search
$search_results = [];
if (isset($_POST['search_tickets'])) {
    $departure = $_POST['departure'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $travel_type = $_POST['travel_type'] ?? '';
    $travel_date = $_POST['travel_date'] ?? '';
    $passengers = $_POST['passengers'] ?? '1';
    
    // Basic validation
    if (empty($departure) || empty($destination) || empty($travel_date)) {
        $search_error = "Please fill in all required fields";
    } else {
        // Filter routes based on search criteria
        foreach ($routes as $route) {
            if ($route['from'] === $departure && 
                $route['to'] === $destination && 
                $route['type'] === $travel_type) {
                $search_results[] = $route;
            }
        }
    }
}

// Handle booking submission
if (isset($_POST['complete_booking'])) {
    $route_id = $_POST['route_id'] ?? '';
    $travel_date = $_POST['travel_date'] ?? '';
    $passengers = $_POST['passengers'] ?? '1';
    $payment_method = $_POST['payment_method'] ?? '';
    
    // In a real app, you would process payment and store booking details
    // Generate a random booking ID
    $booking_id = generateBookingId();
    
    // Set session variables for successful booking
    $_SESSION['booking'] = [
        'id' => $booking_id,
        'route_id' => $route_id,
        'travel_date' => $travel_date,
        'passengers' => $passengers,
        'payment_method' => $payment_method
    ];
    
    // Redirect to booking confirmation page
    header("Location: " . BASE_URL . "?booking_success=1");
    exit;
}

// If booking was just completed, prepare confirmation data
$booking_confirmation = null;
if (isset($_GET['booking_success']) && isset($_SESSION['booking'])) {
    $booking = $_SESSION['booking'];
    $route = null;
    
    // Find route details
    foreach ($routes as $r) {
        if ($r['id'] == $booking['route_id']) {
            $route = $r;
            break;
        }
    }
    
    if ($route) {
        $booking_confirmation = [
            'id' => $booking['id'],
            'passenger' => $_SESSION['user']['name'] ?? 'Guest User',
            'from' => ucfirst($route['from']),
            'to' => ucfirst($route['to']),
            'date' => date('F j, Y', strtotime($booking['travel_date'])),
            'time' => $route['departureTime']
        ];
    }
}
?>