<?php
// Database connection details
$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "uganda_move";

// Define base constants
define('BASE_URL', 'http://localhost/ticketing/');

// Start session
session_start();

// Error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
require_once 'config/db_connect.php';
?>