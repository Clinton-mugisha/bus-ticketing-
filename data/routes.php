<?php
// Include database connection if not already included
if (!function_exists('getDbConnection')) {
    require_once __DIR__ . '/../config/db_connect.php';
}

// Get routes from database
try {
    $conn = getDbConnection();
    
    // Get all active routes with related data
    $routes_query = "
        SELECT r.id, c_from.name as 'from', c_to.name as 'to', 
               tt.name as 'type', r.price, 
               comp.name as 'company',
               TIME_FORMAT(r.departure_time, '%h:%i %p') as departureTime,
               TIME_FORMAT(r.arrival_time, '%h:%i %p') as arrivalTime,
               r.duration, 
               50 as available
        FROM routes r
        JOIN cities c_from ON r.from_city_id = c_from.id
        JOIN cities c_to ON r.to_city_id = c_to.id
        JOIN transport_types tt ON r.transport_type_id = tt.id
        JOIN companies comp ON r.company_id = comp.id
        WHERE r.is_active = 1
        ORDER BY r.from_city_id, r.to_city_id, r.departure_time
    ";
    
    $result = $conn->query($routes_query);
    
    if (!$result) {
        throw new Exception("Error fetching routes: " . $conn->error);
    }
    
    $routes = [];
    while ($row = $result->fetch_assoc()) {
        $routes[] = $row;
    }
    
    // Get popular routes for display
    $popular_routes_query = "
        SELECT c_from.name as 'from', c_to.name as 'to', 
               tt.name as 'type', MIN(r.price) as price,
               r.duration as 'time'
        FROM routes r
        JOIN cities c_from ON r.from_city_id = c_from.id
        JOIN cities c_to ON r.to_city_id = c_to.id
        JOIN transport_types tt ON r.transport_type_id = tt.id
        WHERE r.is_active = 1
        GROUP BY c_from.name, c_to.name, tt.name, r.duration
        ORDER BY COUNT(*) DESC
        LIMIT 4
    ";
    
    $result = $conn->query($popular_routes_query);
    
    if (!$result) {
        throw new Exception("Error fetching popular routes: " . $conn->error);
    }
    
    $popular_routes = [];
    while ($row = $result->fetch_assoc()) {
        $popular_routes[] = $row;
    }
    
    // Get all cities for dropdowns
    $cities_query = "SELECT name FROM cities WHERE is_active = 1 ORDER BY name";
    $result = $conn->query($cities_query);
    
    if (!$result) {
        throw new Exception("Error fetching cities: " . $conn->error);
    }
    
    $cities = [];
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row['name'];
    }
    
    // DO NOT close the connection here - it will be needed by other code
    // $conn->close(); <- REMOVE THIS LINE
    
} catch (Exception $e) {
    error_log("Error in routes.php: " . $e->getMessage());
    
    // Fallback to hardcoded values if database connection fails
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
    
    $popular_routes = [
        ['from' => 'Kampala', 'to' => 'Entebbe', 'type' => 'bus', 'price' => 10000, 'time' => '1 hour'],
        ['from' => 'Kampala', 'to' => 'Jinja', 'type' => 'train', 'price' => 15000, 'time' => '2 hours'],
        ['from' => 'Kampala', 'to' => 'Mbarara', 'type' => 'bus', 'price' => 25000, 'time' => '4 hours'],
        ['from' => 'Kampala', 'to' => 'Gulu', 'type' => 'bus', 'price' => 35000, 'time' => '6 hours']
    ];
    
    $cities = ['kampala', 'entebbe', 'jinja', 'mbale', 'mbarara', 'gulu'];
}

// Function to generate booking ID
function generateBookingId() {
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $id = 'UM';
    
    // Add 2 random letters
    for ($i = 0; $i < 2; $i++) {
        $id .= $letters[rand(0, strlen($letters) - 1)];
    }
    
    // Add 6 random numbers
    for ($i = 0; $i < 6; $i++) {
        $id .= $numbers[rand(0, strlen($numbers) - 1)];
    }
    
    return $id;
}
?>