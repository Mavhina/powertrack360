<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receive data from client
    $name = $_POST['name'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // Process received data
    if (!empty($name) && !empty($role) && !empty($email) && !empty($phone) && !empty($password)) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare an SQL query to insert user data into the database
        $stmt = $conn->prepare("INSERT INTO user (name, role, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $role, $email, $phone, $hashed_password);

        if ($stmt->execute() === TRUE) {
            // Signup successful
            $response['success'] = true;
            $response['message'] = "Signup successful";
        } else {
            // Signup failed
            $response['success'] = false;
            $response['message'] = "Error: Unable to execute query. Please try again.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Please enter valid information for all fields.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>
