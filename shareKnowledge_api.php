<?php
header('Content-Type: application/json');

// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Check if all required parameters are provided
if (!isset($_POST['randomIncidentID']) || !isset($_POST['system_name']) || !isset($_POST['location']) || !isset($_POST['person_name']) || !isset($_POST['description']) || !isset($_POST['fix_description']) || !isset($_POST['date']) || !isset($_POST['part_name'])) {
    echo json_encode(["success" => false, "message" => "Missing parameters"]);
    exit();
}

$incidentID = $_POST['randomIncidentID'];
$systemName = $_POST['system_name'];
$location = $_POST['location'];
$personName = $_POST['person_name'];
$description = $_POST['description'];
$fixDescription = $_POST['fix_description'];
$date = $_POST['date'];
$partName = $_POST['part_name']; // Get the part name

// Prepare SQL statement to insert knowledge sharing record
$sql = "INSERT INTO shared_knowledge (incident_id, system_name, location, person_name, description, fix_description, date, part_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $incidentID, $systemName, $location, $personName, $description, $fixDescription, $date, $partName); // Bind part name

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Knowledge shared successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error sharing knowledge: " . $stmt->error]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
