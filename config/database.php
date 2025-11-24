<?php
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'Social_Sphere';

// ISA LANG PLEASE
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Make $mysqli point to the same connection (ISA LANG SIGE NA WAG IBA IBA)
$mysqli = $conn;

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
