<?php
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

// Get technician_name from query parameters
if (isset($_GET['technician_name'])) {
    $technician_name = $conn->real_escape_string($_GET['technician_name']);

    // Prepare and execute the SQL query
    $sql = "SELECT incident_id FROM TechnicianDeployment WHERE technician_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $technician_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any results are returned
    if ($result->num_rows > 0) {
        // Fetch the incident_id(s) and output them in JSON format
        $incident_ids = array();
        while ($row = $result->fetch_assoc()) {
            $incident_ids[] = $row['incident_id'];
        }
        echo json_encode($incident_ids);
    } else {
        echo json_encode(array("message" => "No incidents found for this technician."));
    }

    $stmt->close();
} else {
    echo json_encode(array("message" => "No technician name provided."));
}

$conn->close();
?>
