<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Function to save notification data to the database
function saveNotificationToDatabase($notificationType, $description, $date, $userid, $isRead) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "kendal";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
        return;
    }

    $sql = "INSERT INTO Notification (notificationtype, description, date, userid, isRead) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
        $conn->close();
        return;
    }

    $stmt->bind_param("ssssi", $notificationType, $description, $date, $userid, $isRead);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Notification saved successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Execute failed: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['notificationType'], $data['description'], $data['date'], $data['userid'], $data['isRead'])) {
    saveNotificationToDatabase(
        htmlspecialchars($data['notificationType']),
        htmlspecialchars($data['description']),
        htmlspecialchars($data['date']),
        intval($data['userid']),
        intval($data['isRead'])
    );
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}
?>
