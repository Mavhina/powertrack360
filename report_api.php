<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

function reportIncidentToDatabase($incidentType, $description, $status, $date, $location, $imagePath, $randomIncidentID, $priority) {
    $servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

    $conn = new mysqli($servername, $username, $password, $dbname);
    $logs = [];

    if ($conn->connect_error) {
        $logs[] = "Connection failed: " . $conn->connect_error;
        echo json_encode(["success" => false, "message" => "Connection failed", "logs" => $logs]);
        return;
    }

    $sql = "INSERT INTO Incidents (typeofincident, description, status, timestamp, location, imagePath, randomIncidentID, priority)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssss", $incidentType, $description, $status, $date, $location, $imagePath, $randomIncidentID, $priority);

        if ($stmt->execute()) {
            $incidentId = $stmt->insert_id;
            $imageURL = $imagePath ? 'http://localhost/team36-main/' . $imagePath : null;
            echo json_encode([
                "success" => true,
                "message" => "Incident reported successfully",
                "incidentid" => $incidentId,
                "imageURL" => $imageURL,
                "logs" => $logs
            ]);
        } else {
            $logs[] = "Error executing SQL statement: " . $stmt->error;
            echo json_encode(["success" => false, "message" => "Error executing SQL statement", "logs" => $logs]);
        }

        $stmt->close();
    } else {
        $logs[] = "Error preparing SQL statement: " . $conn->error;
        echo json_encode(["success" => false, "message" => "Error preparing SQL statement", "logs" => $logs]);
    }

    $conn->close();
}

function saveImage($base64Image) {
    $logs = [];

    if (empty($base64Image)) {
        $logs[] = "No base64 image data provided.";
        return [null, $logs];
    }

    $base64Image = preg_replace('/^data:image\/(jpeg|png|gif);base64,/', '', $base64Image);
    $imageData = base64_decode($base64Image);

    if ($imageData === false) {
        $logs[] = "Failed to decode base64 image.";
        return [null, $logs];
    }

    $imageName = uniqid() . '.jpg';
    $uploadDir = __DIR__ . '/uploads/';
    $imagePath = $uploadDir . $imageName;

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        $logs[] = "Failed to create upload directory: $uploadDir";
        return [null, $logs];
    } elseif (!is_writable($uploadDir)) {
        $logs[] = "Upload directory is not writable: $uploadDir";
        return [null, $logs];
    }

    if (file_put_contents($imagePath, $imageData) === false) {
        $logs[] = "Failed to save image to: $imagePath";
        return [null, $logs];
    }

    $logs[] = "Image saved successfully to: $imagePath";
    return ['uploads/' . $imageName, $logs];
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['incidentType'], $data['description'], $data['incidentStatus'], $data['timestamp'], $data['location'], $data['randomIncidentID'], $data['priority'])) {
    $imagePath = null;
    $logs = [];

    if (isset($data['image']) && !empty($data['image'])) {
        list($imagePath, $imageLogs) = saveImage($data['image']);
        $logs = array_merge($logs, $imageLogs);
    }

    if ($imagePath !== null) {
        reportIncidentToDatabase($data['incidentType'], $data['description'], $data['incidentStatus'], $data['timestamp'], $data['location'], $imagePath, $data['randomIncidentID'], $data['priority']);
    } else {
        echo json_encode(["success" => false, "message" => "Image could not be saved.", "logs" => $logs]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input", "logs" => []]);
}
