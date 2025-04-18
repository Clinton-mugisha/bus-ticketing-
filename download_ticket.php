<?php
// Start session
session_start();

// Define base constants and include DB connection
define('BASE_URL', 'http://localhost/assignments/ticketing/');
require_once 'config/db_connect.php';

// Check if booking ID is provided
if (!isset($_GET['id'])) {
    header("Location: " . BASE_URL);
    exit;
}

$booking_id = $_GET['id'];

// Get ticket data from database
try {
    $conn = getDbConnection();
    
    // Get detailed booking information
    $query = "
        SELECT b.*, 
               u.name as passenger_name,
               c_from.name as from_city, 
               c_to.name as to_city,
               DATE_FORMAT(s.date, '%M %e, %Y') as formatted_date,
               r.departure_time, r.duration,
               comp.name as company_name,
               tt.name as transport_type
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN schedules s ON b.schedule_id = s.id
        JOIN routes r ON s.route_id = r.id
        JOIN cities c_from ON r.from_city_id = c_from.id
        JOIN cities c_to ON r.to_city_id = c_to.id
        JOIN companies comp ON r.company_id = comp.id
        JOIN transport_types tt ON r.transport_type_id = tt.id
        WHERE b.id = ?
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        // We found the booking
        $ticket_data = $result->fetch_assoc();
        
        $ticket = [
            'id' => $ticket_data['id'],
            'passenger' => $ticket_data['passenger_name'],
            'from' => ucfirst($ticket_data['from_city']),
            'to' => ucfirst($ticket_data['to_city']),
            'date' => $ticket_data['formatted_date'],
            'time' => $ticket_data['departure_time'],
            'company' => $ticket_data['company_name'],
            'type' => ucfirst($ticket_data['transport_type']),
            'passengers' => $ticket_data['passengers'],
            'total_price' => $ticket_data['total_price']
        ];
    } else {
        // No booking found with this ID - check session as fallback
        if (isset($_SESSION['booking']) && $_SESSION['booking']['id'] === $booking_id) {
            // Use session data as fallback
            $booking = $_SESSION['booking'];
            
            // Create placeholder ticket data
            $ticket = [
                'id' => $booking_id,
                'passenger' => $_SESSION['user']['name'] ?? 'Guest User',
                'from' => 'Kampala', // Default
                'to' => 'Entebbe',   // Default
                'date' => date('F j, Y', strtotime($booking['travel_date'] ?? 'now')),
                'time' => '10:30 AM',
                'company' => 'Link Bus',
                'type' => 'Bus'
            ];
        } else {
            // No booking found and no fallback available
            header("Location: " . BASE_URL . "?error=booking_not_found");
            exit;
        }
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    error_log("Error retrieving ticket: " . $e->getMessage());
    
    // Fallback to session data if available
    if (isset($_SESSION['booking']) && $_SESSION['booking']['id'] === $booking_id) {
        // Use session data
        $booking = $_SESSION['booking'];
        
        $ticket = [
            'id' => $booking_id,
            'passenger' => $_SESSION['user']['name'] ?? 'Guest User',
            'from' => 'Kampala', // Default
            'to' => 'Entebbe',   // Default
            'date' => date('F j, Y', strtotime($booking['travel_date'] ?? 'now')),
            'time' => '10:30 AM',
            'company' => 'Link Bus',
            'type' => 'Bus'
        ];
    } else {
        header("Location: " . BASE_URL . "?error=ticket_error");
        exit;
    }
}

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
$content .= "TYPE: " . $ticket['type'] . " Service\n";

// Add passengers info if multiple
if (isset($ticket['passengers']) && $ticket['passengers'] > 1) {
    $content .= "PASSENGERS: " . $ticket['passengers'] . "\n";
}

// Add price if available
if (isset($ticket['total_price'])) {
    $content .= "TOTAL PRICE: UGX " . number_format($ticket['total_price']) . "\n";
}

$content .= "\n==============================================\n";
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