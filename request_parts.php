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
if (isset($input['partName']) && isset($input['quantity']) && isset($input['personName']) && isset($input['timestamp']) && isset($input['location']) && isset($input['incidentId']) && isset($input['email'])) {
    $partName = $input['partName'];
    $quantity = $input['quantity'];
    $otherDetails = isset($input['otherDetails']) ? $input['otherDetails'] : '';
    $personName = $input['personName'];
    $timestamp = $input['timestamp'];
    $location = $input['location'];
    $incidentId = $input['incidentId'];
    $email = $input['email']; // Updated to match new column name
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO part_requests (partName, quantity, otherDetails, personName, timestamp, location, email, incidentId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        echo json_encode(array("success" => false, "message" => "Prepare failed: " . $conn->error));
        exit();
    }
    $stmt->bind_param("sissssss", $partName, $quantity, $otherDetails, $personName, $timestamp, $location, $email, $incidentId);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(array("success" => true, "message" => "Part request submitted successfully."));
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to submit part request."));
    }

    // Close statement
    $stmt->close();
} else {
    echo json_encode(array("success" => false, "message" => "Invalid input."));
}

// Close connection
$conn->close();
?>