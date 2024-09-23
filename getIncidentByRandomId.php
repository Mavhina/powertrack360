<?php
// Database connection
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

// Get randomIncidentID from the request
$randomIncidentID = isset($_GET['randomIncidentID']) ? $_GET['randomIncidentID'] : '';

if (!empty($randomIncidentID)) {
    // Prepare SQL query
    $sql = "SELECT * FROM incidents WHERE randomIncidentID = ?";
    
    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $randomIncidentID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any rows are returned
    if ($result->num_rows > 0) {
        $incidents = array();

        // Fetch the incidents
        while ($row = $result->fetch_assoc()) {
            $incidents[] = $row;
        }

        // Return the result as JSON
        echo json_encode(array('status' => 'success', 'data' => $incidents));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'No incidents found.'));
    }

    $stmt->close();
} else {
    echo json_encode(array('status' => 'error', 'message' => 'randomIncidentID is required.'));
}

// Close connection
$conn->close();
?>
