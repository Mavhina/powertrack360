<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate the input data
    if (isset($data['incidentId']) && isset($data['status'])) {
        $incidentId = intval($data['incidentId']);
        $status = $data['status'];

        // Database connection
        $conn = new mysqli('localhost', 'mavhinamulisa', 'mavhina06489mulisa', 'kendal');

        // Check connection
        if ($conn->connect_error) {
            die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
        }

        // Prepare and bind
        $stmt = $conn->prepare("UPDATE Incidents SET status = ? WHERE incidentid = ?");
        $stmt->bind_param("si", $status, $incidentId);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Incident status updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error updating incident: " . $stmt->error]);
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid input"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
