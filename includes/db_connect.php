<?php
// includes/db_connect.php

// Database connection settings
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'company_db';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Database Connection Failed: " . $conn->connect_error);
}

// Optional: Set charset to UTF-8
$conn->set_charset("utf8");
?>
