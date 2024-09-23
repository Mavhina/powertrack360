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

// Prepare the SQL query to get the incident types based on the provided system name
$sql = "SELECT incident_type FROM IncidentsTypes WHERE system_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $systemName);
$stmt->execute();
$result = $stmt->get_result();

// Fetch matching incident types
$incidentTypes = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $incidentTypes[] = array(
            "incident_type" => $row['incident_type']
        );
    }
    echo json_encode(array("success" => true, "incidentTypes" => $incidentTypes));
} else {
    echo json_encode(array("success" => false, "message" => "No matching incident types found for the given system name."));
}

$stmt->close();
$conn->close();
?>
