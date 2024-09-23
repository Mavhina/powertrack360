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

// Get the technician's name from the API request (e.g., via GET or POST)
$technician_name = isset($_GET['technician_name']) ? $_GET['technician_name'] : '';

if ($technician_name) {
    // Prepare the SQL statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT is_active FROM TechnicianDeployment WHERE technician_name = ?");
    $stmt->bind_param("s", $technician_name);

    // Execute the statement
    $stmt->execute();
    $stmt->store_result();

    // Check if there is a match
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($is_active);
        $stmt->fetch();
        
        // Return the boolean value as JSON
        echo json_encode(['result' => (bool)$is_active]);
    } else {
        // No match found, return false
        echo json_encode(['result' => false]);
    }

    $stmt->close();
} else {
    // If no technician_name is provided, return false
    echo json_encode(['result' => false]);
}

$conn->close();
?>
