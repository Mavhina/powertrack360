<?php
header('Content-Type: application/json');

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("success" => false, "message" => "Connection failed: " . $conn->connect_error)));
}

// Get the system name from the request
$systemName = isset($_GET['systemName']) ? $_GET['systemName'] : '';

if (empty($systemName)) {
    echo json_encode(array("success" => false, "message" => "System name is required."));
    exit;
}

// Prepare the SQL query to get the priority level based on the provided system name
$sql = "SELECT priority_level FROM IncidentsTypes WHERE incident_type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $systemName);
$stmt->execute();
$result = $stmt->get_result();

// Fetch priority level
$priorityLevel = '';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $priorityLevel = $row['priority_level'];
    echo json_encode(array("success" => true, "priorityLevel" => $priorityLevel));
} else {
    echo json_encode(array("success" => false, "message" => "No priority level found for the given system name."));
}

$stmt->close();
$conn->close();
?>
