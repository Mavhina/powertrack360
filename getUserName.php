<?php
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

// Check if userid is set in the GET request
if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT name, email FROM User WHERE userid = ?");
    $stmt->bind_param("i", $userid);

    // Execute statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($name, $email);

    // Fetch value
    if ($stmt->fetch()) {
        echo json_encode(array("success" => true, "name" => $name, "email" => $email));
    } else {
        echo json_encode(array("success" => false, "error" => "User not found"));
    }

    // Close statement
    $stmt->close();
} else {
    echo json_encode(array("success" => false, "error" => "No userid provided"));
}

// Close connection
$conn->close();
?>