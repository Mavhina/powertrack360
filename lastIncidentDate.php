<?php
header('Content-Type: application/json');

// Database credentials
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'kendal';

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Query to get the date of the last incident
$sql = "SELECT timestamp FROM Incidents ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of the last row
    $row = $result->fetch_assoc();
    $lastIncidentDate = $row['timestamp'];
} else {
    $lastIncidentDate = 'No incidents found';
}

$conn->close();

// Return the last incident date as JSON
echo json_encode(['lastIncidentDate' => $lastIncidentDate]);
?>
