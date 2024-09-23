<?php
// Database connection details
$host = 'localhost'; // Change if your database is hosted elsewhere
$db_name = 'kendal';
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'part_name' is provided and not empty
if (isset($_GET['part_name']) && !empty(trim($_GET['part_name']))) {
    $part_name = trim($_GET['part_name']);
    error_log("Part Name: " . $part_name); // Log the part name

    // Prepare the SQL statement using prepared statements
    $stmt = $conn->prepare("SELECT Quantity FROM inventory WHERE Part_Name = ?");
    $stmt->bind_param("s", $part_name);
    
    // Execute the statement
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the quantity
        $row = $result->fetch_assoc();
        echo json_encode(["Part_Name" => $part_name, "Quantity" => $row['Quantity']]);
    } else {
        echo json_encode(["error" => "Part not found"]);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(["error" => "No part name provided or part name is empty"]);
}

// Close the connection
$conn->close();
?>
