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
    file_put_contents('php://stderr', print_r($data, true));

    // Check if all required fields are provided
    if (!empty($data->notificationtype) && !empty($data->description) && !empty($data->date) && !empty($data->userid) && !empty($data->incidentid)) {
        // Sanitize the input data
        $notificationType = sanitizeInput($data->notificationtype);
        $description = sanitizeInput($data->description);
        $date = sanitizeInput($data->date);
        $userId = (int)$data->userid; // Convert to integer
        $incidentId = (int)$data->incidentid; // Convert to integer

        // Insert the notification into the database
        $query = "INSERT INTO Notification (notificationtype, description, date, userid, isRead, incidentid) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($query)) {
            $isRead = 0; // Set default value for isRead
            $stmt->bind_param("sssiii", $notificationType, $description, $date, $userId, $isRead, $incidentId);

            if ($stmt->execute()) {
                echo json_encode(array("success" => true, "message" => "Notification saved successfully."));
            } else {
                echo json_encode(array("success" => false, "message" => "Failed to save notification. Error: " . $stmt->error));
                file_put_contents('php://stderr', "SQL Error: " . $stmt->error . "\n", FILE_APPEND);
            }

            $stmt->close();
        } else {
            echo json_encode(array("success" => false, "message" => "Failed to prepare the SQL statement. Error: " . $conn->error));
            file_put_contents('php://stderr', "Preparation Error: " . $conn->error . "\n", FILE_APPEND);
        }
    } else {
        echo json_encode(array("success" => false, "message" => "All fields are required."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid request method."));
}

// Close the database connection
$conn->close();
?>
