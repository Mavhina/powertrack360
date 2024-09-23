<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("success" => false, "message" => "Database connection failed: " . $conn->connect_error)));
}

// Function to sanitize inputs
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted data
    $data = json_decode(file_get_contents("php://input"));

    // Log the incoming data
    file_put_contents('php://stderr', "Data received: " . print_r($data, true) . "\n", FILE_APPEND);

    // Check if all required fields are provided
    if (!empty($data->notificationtype) && !empty($data->description) && !empty($data->date) && !empty($data->userid) && !empty($data->incidentid) && !empty($data->randomIncidentID)) {
        // Log each field
        file_put_contents('php://stderr', "NotificationType: " . $data->notificationtype . "\n", FILE_APPEND);
        file_put_contents('php://stderr', "Description: " . $data->description . "\n", FILE_APPEND);
        file_put_contents('php://stderr', "Date: " . $data->date . "\n", FILE_APPEND);
        file_put_contents('php://stderr', "UserId: " . $data->userid . "\n", FILE_APPEND);
        file_put_contents('php://stderr', "IncidentId: " . $data->incidentid . "\n", FILE_APPEND);
        file_put_contents('php://stderr', "RandomIncidentID: " . $data->randomIncidentID . "\n", FILE_APPEND);
        
        // Insert the notification into the database
        $query = "INSERT INTO LowNotification (notificationtype, description, date, userid, isRead, incidentid, randomIncD) VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($query)) {
            $isRead = 0; // Set default value for isRead
            $stmt->bind_param("sssiiis", $data->notificationtype, $data->description, $data->date, $data->userid, $isRead, $data->incidentid, $data->randomIncidentID);

            if ($stmt->execute()) {
                echo json_encode(array("success" => true, "message" => "Notification saved successfully."));
            } else {
                // Log detailed SQL error
                file_put_contents('php://stderr', "SQL Error: " . $stmt->error . "\n", FILE_APPEND);
                echo json_encode(array("success" => false, "message" => "Failed to save notification. Error: " . $stmt->error));
            }

            $stmt->close();
        } else {
            // Log preparation error
            file_put_contents('php://stderr', "Preparation Error: " . $conn->error . "\n", FILE_APPEND);
            echo json_encode(array("success" => false, "message" => "Failed to prepare the SQL statement. Error: " . $conn->error));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "All fields are required."));
        file_put_contents('php://stderr', "Missing Fields: " . print_r($data, true) . "\n", FILE_APPEND);
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method."));
}

// Close the database connection
$conn->close();
?>
