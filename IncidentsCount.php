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

// SQL query to count incidents by type
$sql = "SELECT typeofincident, COUNT(*) as count FROM Incidents GROUP BY typeofincident";
$result = $conn->query($sql);

$incidentData = array();

// Fetch data and store it in an array
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $incidentData[] = $row;
    }
} else {
    echo json_encode(array("message" => "No data found"));
    exit;
}

// Close the connection
$conn->close();

// Return the data as a JSON response
echo json_encode($incidentData);
?>
