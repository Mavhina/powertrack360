<?php
header('Content-Type: application/json');

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select all records from the inspections table
$sql = "SELECT id, building_name, inspection_date, start_time, end_time, description FROM inspections";
$result = $conn->query($sql);

$inspections = array();

// Check if the query returned any rows
if ($result->num_rows > 0) {
    // Fetch all rows and add them to the array
    while ($row = $result->fetch_assoc()) {
        $inspections[] = $row;
    }
} else {
    // No records found
    $inspections = array('message' => 'No inspections found');
}

// Return the results as JSON
echo json_encode($inspections);

// Close the connection
$conn->close();
?>
