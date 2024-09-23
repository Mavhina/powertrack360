<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT deployment_id, technician_name, technician_email, incident_id, incident_description, deployment_date,IncStatus FROM TechnicianDeployment";
$result = $conn->query($sql);

if ($result) {
    $deployments = [];
    while($row = $result->fetch_assoc()) {
        $deployments[] = $row;
    }
    echo json_encode(["success" => true, "deployments" => $deployments]);
} else {
    echo json_encode(["success" => false, "message" => "Error fetching deployments: " . $conn->error]);
}

$conn->close();
?>
