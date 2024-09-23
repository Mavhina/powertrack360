<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("success" => false, "error" => "Connection failed: " . $conn->connect_error)));
}

// Query to get data from UpcomingMaintenance
$sql = "SELECT id, placeName, systemName, description, date FROM UpcomingMaintenance";
$result = $conn->query($sql);

// Check for SQL errors
if ($result === false) {
    die(json_encode(array("success" => false, "error" => "SQL Error: " . $conn->error)));
}

// Fetch data and return as JSON
if ($result->num_rows > 0) {
    $maintenances = array();
    while ($row = $result->fetch_assoc()) {
        $maintenances[] = $row;
    }
    echo json_encode(array("success" => true, "upcomingMaintenance" => $maintenances));
} else {
    echo json_encode(array("success" => false, "error" => "No upcoming maintenance found"));
}

// Close connection
$conn->close();
?>
