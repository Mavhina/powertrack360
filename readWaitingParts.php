<?php
header("Content-Type: application/json");

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => 'Connection failed: ' . $conn->connect_error)));
}

// Query to select all records from waitingparts
$sql = "SELECT Status, Description, EstimatedTime, name FROM waitingparts";
$result = $conn->query($sql);

$response = array();

if ($result->num_rows > 0) {
    // Fetch all rows into an array
    while($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode(array(
        'success' => true,
        'data' => $response,
        'message' => 'Records fetched successfully'
    ));
} else {
    echo json_encode(array(
        'success' => false,
        'message' => 'No records found'
    ));
}

$conn->close();
?>
