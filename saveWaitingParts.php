<?php
// saveWaitingParts.php
header("Content-Type: application/json");

// Database configuration
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

// Retrieve data from JSON POST request
$data = json_decode(file_get_contents('php://input'), true);

// Extract data
$status = isset($data['Status']) ? $data['Status'] : '';
$description = isset($data['Description']) ? $data['Description'] : '';
$estimatedTime = isset($data['EstimatedTime']) ? $data['EstimatedTime'] : '';
$name = isset($data['name']) ? $data['name'] : '';

$response = array();

if (!empty($status) && !empty($description) && !empty($estimatedTime) && !empty($name)) {
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO waitingparts (Status, Description, EstimatedTime, name) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $status, $description, $estimatedTime, $name);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Record successfully inserted";
    } else {
        $response['success'] = false;
        $response['message'] = "Failed to insert record";
    }

    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = "All fields are required";
}

$conn->close();
echo json_encode($response);
?>
