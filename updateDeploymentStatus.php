<?php
header('Content-Type: application/json');

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read and decode JSON data from the request body
$input = json_decode(file_get_contents('php://input'), true);
$technician_name = isset($input['technician_name']) ? $input['technician_name'] : '';
$is_active = isset($input['is_active']) ? (int)$input['is_active'] : 1;

if ($technician_name) {
    // Prepare the SQL statement to avoid SQL injection
    $stmt = $conn->prepare("UPDATE TechnicianDeployment SET is_active = ? WHERE technician_name = ?");
    $stmt->bind_param("is", $is_active, $technician_name);

    // Execute the statement
    if ($stmt->execute()) {
        $affectedRows = $stmt->affected_rows;
        if ($affectedRows > 0) {
            echo json_encode(['success' => true, 'affectedRows' => $affectedRows]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No rows affected, please check the technician name']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    // If no technician_name is provided, return an error
    echo json_encode(['success' => false, 'error' => 'No technician name provided']);
}

$conn->close();
?>
