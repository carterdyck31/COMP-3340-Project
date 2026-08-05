<?php
// database connection settings
$host = "localhost";
$user = "dyck61_admin";
$pass = "comp3340password1239782";
$dbname = "dyck61_3340_Project";

$conn = new mysqli($host, $user, $pass, $dbname);

// check if connection failed
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>