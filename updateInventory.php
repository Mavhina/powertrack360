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

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['part_name']) || !isset($data['quantity'])) {
    echo json_encode(array("success" => false, "message" => "Missing part_name or quantity"));
    exit();
}

// Sanitize inputs
$part_name = trim($conn->real_escape_string($data['part_name']));
$quantity = intval($data['quantity']); // Ensure quantity is an integer

// Check if the part exists
$checkSql = "SELECT * FROM inventory WHERE Part_Name = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $part_name);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    echo json_encode(array("success" => false, "message" => "No part found with the specified name."));
    exit();
}

// Prepare the update query
$sql = "UPDATE inventory SET Quantity = ? WHERE Part_Name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $quantity, $part_name);

// Execute the query
if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Quantity updated successfully."));
} else {
    echo json_encode(array("success" => false, "message" => "Error updating quantity: " . $stmt->error));
}

// Close connections
$stmt->close();
$conn->close();
?>
