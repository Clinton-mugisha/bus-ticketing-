<?php
// Sample data - In a real app, this would come from a database
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

// Popular routes for display
$popular_routes = [
    ['from' => 'Kampala', 'to' => 'Entebbe', 'type' => 'bus', 'price' => 10000, 'time' => '1 hour'],
    ['from' => 'Kampala', 'to' => 'Jinja', 'type' => 'train', 'price' => 15000, 'time' => '2 hours'],
    ['from' => 'Kampala', 'to' => 'Mbarara', 'type' => 'bus', 'price' => 25000, 'time' => '4 hours'],
    ['from' => 'Kampala', 'to' => 'Gulu', 'type' => 'bus', 'price' => 35000, 'time' => '6 hours']
];

// Cities for departure/destination dropdowns
$cities = ['kampala', 'entebbe', 'jinja', 'mbale', 'mbarara', 'gulu'];

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