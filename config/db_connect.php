<?php
// Database connection file
function getDbConnection() {
    global $db_host, $db_username, $db_password, $db_name;
    
    // Create connection
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    
    // Check connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to prevent "weird" characters in database
    $conn->set_charset("utf8mb4");
    
    return $conn;
}
?>
