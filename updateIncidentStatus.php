<?php
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(array("success" => false, "message" => "Connection failed: " . $conn->connect_error));
    exit();
}

// Get the posted data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (isset($input['incident_id'])) {
    $incident_id = $input['incident_id'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE TechnicianDeployment SET IncStatus = 'Closed' WHERE incident_id = ?");
    if ($stmt === false) {
        echo json_encode(array("success" => false, "message" => "Prepare failed: " . $conn->error));
        exit();
    }
    // Bind as a string since incident_id is a varchar field
    $stmt->bind_param("s", $incident_id);

    // Execute the statement
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(array("success" => true, "message" => "Incident status updated to Closed."));
        } else {
            echo json_encode(array("success" => false, "message" => "No record found with the given incident_id."));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to update incident status."));
    }

    // Close statement
    $stmt->close();
} else {
    echo json_encode(array("success" => false, "message" => "Invalid input."));
}

// Close connection
$conn->close();
?>
