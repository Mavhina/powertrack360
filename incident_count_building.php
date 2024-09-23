<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    $response = [
        "success" => false,
        "message" => "Connection failed",
        "logs" => ["Connection failed: " . $conn->connect_error]
    ];
    echo json_encode($response);
    exit();
}

// Define the building names and their locations
$buildings = [
    'Building A, Room 201',
    'Building B, Room 202',
    'Building C, Room 203'
];

$result = [];

// Prepare the SQL statement
$stmt = $conn->prepare("SELECT location, COUNT(*) as incident_count FROM incidents WHERE location = ? GROUP BY location");

// Execute the statement for each building
foreach ($buildings as $building) {
    $stmt->bind_param("s", $building);
    $stmt->execute();
    $stmt->bind_result($location, $incident_count);
    
    if ($stmt->fetch()) {
        $result[] = [
            "building" => $location,
            "number_of_incidents" => $incident_count
        ];
    }
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the result as JSON
$response = [
    "success" => true,
    "data" => $result
];
echo json_encode($response);
?>
