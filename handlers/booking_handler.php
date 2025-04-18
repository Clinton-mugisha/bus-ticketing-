<?php
// Include database connection if not already included
require_once __DIR__ . '/../config/db_connect.php';

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
    $payment_phone = $_POST['phone_number'] ?? '';
    
    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        // Redirect to login page with a message
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        $_SESSION['login_message'] = "Please log in to complete your booking";
        header("Location: " . BASE_URL . "?show_login=1");
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    
    try {
        $conn = getDbConnection();
        
        // Start transaction
        $conn->begin_transaction();
        
        // Get the selected route details
        $route_stmt = $conn->prepare("SELECT r.*, t.name as transport_type, c.name as company_name 
                                    FROM routes r 
                                    JOIN transport_types t ON r.transport_type_id = t.id
                                    JOIN companies c ON r.company_id = c.id
                                    WHERE r.id = ?");
        $route_stmt->bind_param("i", $route_id);
        $route_stmt->execute();
        $route_result = $route_stmt->get_result();
        
        if ($route_result->num_rows === 0) {
            throw new Exception("Route not found");
        }
        
        $route = $route_result->fetch_assoc();
        $total_price = $route['price'] * $passengers;
        
        // Check if a schedule exists for this route and date, or create one
        $schedule_stmt = $conn->prepare("SELECT id, available_seats FROM schedules 
                                        WHERE route_id = ? AND date = ? AND is_active = 1");
        $schedule_stmt->bind_param("is", $route_id, $travel_date);
        $schedule_stmt->execute();
        $schedule_result = $schedule_stmt->get_result();
        
        if ($schedule_result->num_rows === 0) {
            // Create a new schedule with default 50 seats
            $default_seats = 50;
            $available_seats = $default_seats;
            
            $create_schedule_stmt = $conn->prepare("INSERT INTO schedules 
                                                (route_id, date, total_seats, available_seats) 
                                                VALUES (?, ?, ?, ?)");
            $create_schedule_stmt->bind_param("isii", $route_id, $travel_date, $default_seats, $available_seats);
            $create_schedule_stmt->execute();
            
            $schedule_id = $conn->insert_id;
            $available_seats = $default_seats;
        } else {
            $schedule = $schedule_result->fetch_assoc();
            $schedule_id = $schedule['id'];
            $available_seats = $schedule['available_seats'];
        }
        
        // Check if enough seats are available
        if ($available_seats < $passengers) {
            throw new Exception("Not enough seats available for this schedule");
        }
        
        // Generate booking ID
        $booking_id = generateBookingId();
        
        // Create booking record
        $booking_stmt = $conn->prepare("INSERT INTO bookings 
                                    (id, user_id, schedule_id, passengers, total_price, 
                                    payment_method, payment_phone, payment_status) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, 'completed')");
        $booking_stmt->bind_param("siiiiss", 
                                $booking_id, $user_id, $schedule_id, $passengers, 
                                $total_price, $payment_method, $payment_phone);
        $booking_stmt->execute();
        
        // Update available seats
        $update_seats_stmt = $conn->prepare("UPDATE schedules 
                                            SET available_seats = available_seats - ? 
                                            WHERE id = ?");
        $update_seats_stmt->bind_param("ii", $passengers, $schedule_id);
        $update_seats_stmt->execute();
        
        // Commit the transaction
        $conn->commit();
        
        // Set session variables for successful booking
        $_SESSION['booking'] = [
            'id' => $booking_id,
            'route_id' => $route_id,
            'travel_date' => $travel_date,
            'passengers' => $passengers,
            'payment_method' => $payment_method,
            'total_price' => $total_price,
            'from_city' => $route['from_city_id'],
            'to_city' => $route['to_city_id'],
            'company' => $route['company_name'],
            'transport_type' => $route['transport_type']
        ];
        
        // Close all statements
        $route_stmt->close();
        $schedule_stmt->close();
        if (isset($create_schedule_stmt)) $create_schedule_stmt->close();
        $booking_stmt->close();
        $update_seats_stmt->close();
        
        // Redirect to booking confirmation page
        header("Location: " . BASE_URL . "?booking_success=1");
        exit;
        
    } catch (Exception $e) {
        // Roll back the transaction in case of error
        if (isset($conn) && $conn instanceof mysqli && $conn->ping()) {
            $conn->rollback();
        }
        
        // Set error message
        $booking_error = "Booking failed: " . $e->getMessage();
        error_log("Booking error: " . $e->getMessage());
    }
}

// If booking was just completed, prepare confirmation data
$booking_confirmation = null;
if (isset($_GET['booking_success']) && isset($_SESSION['booking'])) {
    try {
        $booking = $_SESSION['booking'];
        $booking_id = $booking['id'];
        
        $conn = getDbConnection();
        
        // Get booking details from database
        $stmt = $conn->prepare("
            SELECT b.*, 
                   u.name as passenger_name,
                   c_from.name as from_city, 
                   c_to.name as to_city,
                   DATE_FORMAT(s.date, '%M %e, %Y') as formatted_date,
                   r.departure_time
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            JOIN schedules s ON b.schedule_id = s.id
            JOIN routes r ON s.route_id = r.id
            JOIN cities c_from ON r.from_city_id = c_from.id
            JOIN cities c_to ON r.to_city_id = c_to.id
            WHERE b.id = ?
        ");
        $stmt->bind_param("s", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $booking_data = $result->fetch_assoc();
            
            $booking_confirmation = [
                'id' => $booking_data['id'],
                'passenger' => $booking_data['passenger_name'],
                'from' => ucfirst($booking_data['from_city']),
                'to' => ucfirst($booking_data['to_city']),
                'date' => $booking_data['formatted_date'],
                'time' => $booking_data['departure_time'],
                'passengers' => $booking_data['passengers'],
                'total_price' => $booking_data['total_price']
            ];
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        error_log("Error retrieving booking confirmation: " . $e->getMessage());
        
        // Fallback to session data if database query fails
        $booking = $_SESSION['booking'];
        // Use the existing fallback/dummy data approach as defined in the original file
        // This is a simplified version for demonstration
        $booking_confirmation = [
            'id' => $booking['id'],
            'passenger' => $_SESSION['user']['name'] ?? 'Guest User',
            'from' => ucfirst($booking['from_city']),
            'to' => ucfirst($booking['to_city']),
            'date' => date('F j, Y', strtotime($booking['travel_date'])),
            'time' => '10:30 AM', // This would be better to get from the route data
            'passengers' => $booking['passengers'],
            'total_price' => $booking['total_price']
        ];
    }
}
?>