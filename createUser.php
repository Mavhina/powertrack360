<?php

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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user signup data from POST request
    $name = $_POST['name'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // Debugging: Print received data
    echo "Debugging: Received data - Name: $name, Role: $role, Email: $email, Password: $password, Phone: $phone";

    // Validate input (you can add more validation as needed)
    if (empty($name) || empty($role) || empty($email) || empty($password) || empty($phone)) {
        // Debugging: Print values of empty fields
        echo "Debugging: Empty fields - Name: $name, Role: $role, Email: $email, Password: $password, Phone: $phone";

        // Return error response if any required field is empty
        http_response_code(400); // Bad Request
        echo json_encode(array("success" => false, "message" => "Please provide all required fields."));
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Debugging: Print hashed password
    echo "Debugging: Hashed Password: $hashed_password";

    // Prepare and execute SQL query to insert user data
    $stmt = $conn->prepare("INSERT INTO User (name, role, email, password, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $role, $email, $hashed_password, $phone);

    if ($stmt->execute()) {
        // User created successfully
        http_response_code(201); // Created
        echo json_encode(array("success" => true, "message" => "User created successfully."));
    } else {
        // Error occurred while creating user
        // Debugging: Print error message
        echo "Debugging: Error message: " . $conn->error;

        http_response_code(500); // Internal Server Error
        echo json_encode(array("success" => false, "message" => "Unable to create user."));
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("success" => false, "message" => "Method not allowed."));
}
?>
