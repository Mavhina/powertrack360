<?php
header('Content-Type: application/json');

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

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['name'])) {
    echo json_encode(array("success" => false, "message" => "Missing name parameter"));
    exit();
}

// Sanitize input
$name = $conn->real_escape_string($data['name']);

// Prepare the delete query
$sql = "DELETE FROM waitingparts WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);

// Execute the query
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(array("success" => true, "message" => "Entries deleted successfully."));
    } else {
        echo json_encode(array("success" => true, "message" => "Entries deleted successfully."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Error deleting entries: " . $stmt->error));
}

// Close connections
$stmt->close();
$conn->close();
?>
