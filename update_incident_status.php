<?php

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Check if all required parameters are provided
if (!isset($_POST['randomIncidentID']) || !isset($_POST['status'])) {
    error_log("Missing parameters: randomIncidentID or status");
    echo json_encode(["success" => false, "message" => "Missing parameters"]);
    exit();
}

$randomIncidentID = $_POST['randomIncidentID'];
$status = $_POST['status'];

// Prepare SQL statement (assuming randomIncidentID is the primary key)
$sql = "UPDATE Incidents SET status='$status' WHERE randomIncidentID='$randomIncidentID'";

if ($conn->query($sql) === TRUE) {
    error_log("Incident updated successfully for randomIncidentID: $randomIncidentID");
    echo json_encode(["success" => true]);
} else {
    error_log("Error updating incident status: " . $conn->error);
    echo json_encode(["success" => false, "message" => "Error updating incident status: " . $conn->error]);
}

// Close connection
$conn->close();



?>
