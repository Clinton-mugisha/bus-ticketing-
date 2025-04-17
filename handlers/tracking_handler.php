<?php
// Handle journey tracking
$tracking_info = null;
if (isset($_POST['track_journey'])) {
    $booking_id = $_POST['booking_id'] ?? '';
    
    if (empty($booking_id)) {
        $tracking_error = "Please enter a booking ID";
    } else {
        // In a real app, you would query the database for booking details
        // For demo, just check if it starts with 'UM'
        if (strpos($booking_id, 'UM') === 0) {
            $tracking_info = [
                'id' => $booking_id,
                'from' => 'Kampala',
                'to' => 'Entebbe',
                'progress' => 65,
                'eta' => '10:30 AM',
                'remaining' => '35 minutes',
                'updates' => [
                    ['time' => '10:05 AM', 'info' => 'Vehicle is currently at Kajjansi'],
                    ['time' => '09:45 AM', 'info' => 'Departed from Kampala Bus Terminal']
                ]
            ];
        } else {
            $tracking_error = "Invalid booking ID. Please check and try again.";
        }
    }
}
?>