<?php
header('Content-Type: application/json');

// Function to fetch incidents from the database
function fetchIncidentsFromDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "kendal";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
    }

    $sql = "SELECT * FROM Incidents";
    $result = $conn->query($sql);

    if ($result) {
        $incidents = [];
        while($row = $result->fetch_assoc()) {
            if (!empty($row['imagePath'])) {
                $imagePath = 'http://localhost/team36-main/api/' . $row['imagePath'];
                $row['image'] = $imagePath;
            }
            $incidents[] = $row;
        }
        echo json_encode(["success" => true, "incidents" => $incidents]);
    } else {
        echo json_encode(["success" => false, "message" => "Error fetching incidents: " . $conn->error]);
    }

    $conn->close();
}

// Function to fetch a specific incident from the database
function fetchIncidentById($id) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "kendal";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
    }

    $sql = $conn->prepare("SELECT * FROM Incidents WHERE id = ?");
    $sql->bind_param('i', $id);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $incident = $result->fetch_assoc();
        if (!empty($incident['imagePath'])) {
            $imagePath = 'http://localhost/team36-main/api/' . $incident['imagePath'];
            $incident['image'] = $imagePath;
        }
        echo json_encode(["success" => true, "incident" => $incident]);
    } else {
        echo json_encode(["success" => false, "message" => "No incident found with the given id"]);
    }

    $conn->close();
}

// Handle request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        fetchIncidentById($id);
    } else {
        fetchIncidentsFromDatabase();
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
