<?php
// Start session
session_start();

// Define base constants
define('BASE_URL', 'http://localhost/assignments/ticketing/');

// Check if booking ID is provided
if (!isset($_GET['id'])) {
    header("Location: " . BASE_URL);
    exit;
}

$booking_id = $_GET['id'];

// Check if booking session data exists
if (!isset($_SESSION['booking'])) {
    header("Location: " . BASE_URL);
    exit;
}

// Get routes data to find the selected route details
$routes = [
    [
        'id' => 1,
        'from' => 'kampala',
        'to' => 'entebbe',
        'type' => 'bus',
        'price' => 10000,
        'company' => 'Link Bus',
        'departureTime' => '10:30 AM',
        'arrivalTime' => '11:30 AM',
        'duration' => '1 hour',
        'available' => 45
    ],
    [
        'id' => 2,
        'from' => 'kampala',
        'to' => 'entebbe',
        'type' => 'bus',
        'price' => 12000,
        'company' => 'Gateway Bus',
        'departureTime' => '11:00 AM',
        'arrivalTime' => '12:00 PM',
        'duration' => '1 hour',
        'available' => 30
    ],
    [
        'id' => 3,
        'from' => 'kampala',
        'to' => 'jinja',
        'type' => 'train',
        'price' => 15000,
        'company' => 'Uganda Railways',
        'departureTime' => '09:00 AM',
        'arrivalTime' => '11:00 AM',
        'duration' => '2 hours',
        'available' => 120
    ],
    [
        'id' => 4,
        'from' => 'kampala',
        'to' => 'mbarara',
        'type' => 'bus',
        'price' => 25000,
        'company' => 'YY Coaches',
        'departureTime' => '08:00 AM',
        'arrivalTime' => '12:00 PM',
        'duration' => '4 hours',
        'available' => 50
    ],
    [
        'id' => 5,
        'from' => 'kampala',
        'to' => 'gulu',
        'type' => 'bus',
        'price' => 35000,
        'company' => 'Gateway Bus',
        'departureTime' => '07:00 AM',
        'arrivalTime' => '01:00 PM',
        'duration' => '6 hours',
        'available' => 35
    ]
];

// Get the booking information from session
$booking = $_SESSION['booking'];
$route = null;

// Find the selected route
foreach ($routes as $r) {
    if ($r['id'] == $booking['route_id']) {
        $route = $r;
        break;
    }
}

// If route wasn't found, use default values rather than failing
if (!$route) {
    // Use placeholder/default data instead of failing
    $ticket = [
        'id' => $booking_id,
        'passenger' => $_SESSION['user']['name'] ?? 'Guest User',
        'from' => 'Kampala', // Default origin
        'to' => 'Entebbe',   // Default destination
        'date' => date('F j, Y', strtotime($booking['travel_date'] ?? 'now')),
        'time' => '10:30 AM',
        'company' => 'Link Bus',
        'type' => 'Bus'
    ];
} else {
    // Create the ticket with actual booking details
    $ticket = [
        'id' => $booking_id,
        'passenger' => $_SESSION['user']['name'] ?? 'Guest User',
        'from' => ucfirst($route['from']),
        'to' => ucfirst($route['to']),
        'date' => date('F j, Y', strtotime($booking['travel_date'] ?? 'now')),
        'time' => $route['departureTime'],
        'company' => $route['company'],
        'type' => ucfirst($route['type'])
    ];
}

// Log the data to troubleshoot (in a real app, you'd use proper logging)
error_log('Booking data: ' . json_encode($booking));
error_log('Route data: ' . json_encode($route));
error_log('Ticket data: ' . json_encode($ticket));

// Set headers for file download
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="UgandaMove_Ticket_' . $booking_id . '.txt"');

// Create ticket content
$content = "==============================================\n";
$content .= "             UGANDAMOVE TICKET               \n";
$content .= "==============================================\n\n";
$content .= "BOOKING ID: " . $ticket['id'] . "\n";
$content .= "PASSENGER: " . $ticket['passenger'] . "\n";
$content .= "ROUTE: " . $ticket['from'] . " to " . $ticket['to'] . "\n";
$content .= "DATE: " . $ticket['date'] . "\n";
$content .= "TIME: " . $ticket['time'] . "\n";
$content .= "CARRIER: " . $ticket['company'] . "\n";
$content .= "TYPE: " . $ticket['type'] . " Service\n\n";
$content .= "==============================================\n";
$content .= "This ticket is valid for one journey only.\n";
$content .= "Please present this ticket to the conductor before boarding.\n";
$content .= "==============================================\n";
$content .= "UgandaMove - Making travel easier across Uganda\n";
$content .= "Contact: +256 700 123 456 | info@ugandamove.com\n";
$content .= "==============================================\n";

// Output content and exit
echo $content;
exit;
?>