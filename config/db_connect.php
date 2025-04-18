<?php
// Database connection file

// Store a single connection instance to avoid repeated connections
$GLOBALS['db_connection'] = null;

function getDbConnection() {
    global $db_host, $db_username, $db_password, $db_name;
    
    // If we already have an active connection, return it
    if (isset($GLOBALS['db_connection']) && 
        $GLOBALS['db_connection'] instanceof mysqli && 
        $GLOBALS['db_connection']->ping()) {
        return $GLOBALS['db_connection'];
    }
    
    // Create new connection
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    
    // Check connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to prevent "weird" characters in database
    $conn->set_charset("utf8mb4");
    
    // Store connection for reuse
    $GLOBALS['db_connection'] = $conn;
    
    return $conn;
}

// Function to safely close the database connection - only use when you're sure it's no longer needed
function closeDbConnection() {
    if (isset($GLOBALS['db_connection']) && $GLOBALS['db_connection'] instanceof mysqli) {
        $GLOBALS['db_connection']->close();
        $GLOBALS['db_connection'] = null;
    }
}
?>
