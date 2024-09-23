<?php
// Database credentials
$host = 'localhost'; // Adjust if necessary
$dbname = 'kendal';  // Your database name
$username = 'root';  // Your database username
$password = '';      // Your database password

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the system_name parameter is passed via GET request
if (isset($_GET['system_name'])) {
    // Sanitize input to prevent SQL injection
    $system_name = $conn->real_escape_string($_GET['system_name']);

    // Query the database to get parts for the provided system_name
    $sql = "SELECT Part_Name, Quantity FROM inventory WHERE System_Name = '$system_name'";
    $result = $conn->query($sql);

    // Check if any results were returned
    if ($result->num_rows > 0) {
        // Create an array to store the parts data
        $parts = array();

        // Fetch all rows and add them to the parts array
        while ($row = $result->fetch_assoc()) {
            $parts[] = $row;
        }

        // Return the results in JSON format
        echo json_encode($parts);
    } else {
        // If no parts found for the system_name, return an empty array
        echo json_encode([]);
    }
} else {
    // If system_name is not passed, return an error message
    echo json_encode(["error" => "system_name parameter is required"]);
}

// Close the database connection
$conn->close();
?>
