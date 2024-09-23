<?php
header("Content-Type: application/json");

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("success" => false, "message" => "Connection failed: " . $conn->connect_error)));
}

// Get incident_id from the request
$data = json_decode(file_get_contents("php://input"), true);
$incident_id = $data['incident_id'] ?? null;

if (!$incident_id) {
    echo json_encode(array("success" => false, "message" => "Incident ID is required."));
    exit();
}

// Prepare and bind
$stmt = $conn->prepare("UPDATE TechnicianDeployment SET IncStatus = 'Closed', is_active = 0 WHERE incident_id = ?");
$stmt->bind_param("s", $incident_id);

if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Incident status updated successfully."));
} else {
    echo json_encode(array("success" => false, "message" => "Error updating incident status: " . $conn->error));
}

$stmt->close();
$conn->close();
?>
