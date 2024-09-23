<?php
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

// SQL query to count incidents based on status
$sql = "SELECT status, COUNT(*) as count FROM Incidents GROUP BY status";
$result = $conn->query($sql);

$counts = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $counts[$row['status']] = $row['count'];
    }
}

// Close connection
$conn->close();

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($counts);
?>
