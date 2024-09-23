<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Include database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(array("success" => false, "error" => "Connection failed: " . $conn->connect_error)));
}

// Update this line to use the correct column for ordering
$sql = "SELECT id, title, description FROM NewsAndUpdates ORDER BY id DESC"; // Adjust column name as needed

$result = $conn->query($sql);

if ($result === false) {
    die(json_encode(array("success" => false, "error" => "SQL Error: " . $conn->error)));
}

if ($result->num_rows > 0) {
    $incidents = array();
    while ($row = $result->fetch_assoc()) {
        $incidents[] = $row;
    }
    echo json_encode(array("success" => true, "incidents" => $incidents));
} else {
    echo json_encode(array("success" => false, "error" => "No news or updates found"));
}

$conn->close();
?>
