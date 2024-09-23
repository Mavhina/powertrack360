<?php
// Include database connection file
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("success" => false, "message" => "Connection failed: " . $conn->connect_error)));
}

// Check if 'incidentid' parameter is provided
if (isset($_GET['incidentid'])) {
    $incidentId = $_GET['incidentid'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM incidents WHERE incidentid = ?");
    $stmt->bind_param("i", $incidentId);

    // Execute the statement
    $stmt->execute();

    // Fetch the result
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $incident = $result->fetch_assoc();
        echo json_encode(['success' => true, 'incident' => $incident]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No incident found']);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close(); // Ensure the connection is closed
} else {
    echo json_encode(['success' => false, 'message' => 'Incident ID not provided']);
}
?>
