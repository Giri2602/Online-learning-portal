<?php
// Database configuration
$host = 'localhost';        // Database host
$db   = 'online_learning';  // Database name
$user = 'root';             // Database username (default is 'root' for XAMPP)
$pass = '';                 // Database password (leave blank for XAMPP)
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}