<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create a new mysqli connection
$mysqli = new mysqli($servername, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the incident_id from the request
$incident_id = $_GET['incident_id'];

// Validate the input (basic sanitization)
if (empty($incident_id)) {
    echo json_encode(['success' => false, 'message' => 'Incident ID is required']);
    exit();
}

// Prepare the SQL query
$query = "SELECT date FROM shared_knowledge WHERE incident_id = ?";

// Prepare and execute the statement
if ($stmt = $mysqli->prepare($query)) {
    $stmt->bind_param('s', $incident_id); 
    $stmt->execute();
    $stmt->bind_result($date);

    // Fetch the result
    if ($stmt->fetch()) {
        echo json_encode(['success' => true, 'date' => $date]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Incident ID not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Database query failed']);
}

$mysqli->close();
?>
