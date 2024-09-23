<?php
header('Content-Type: application/json');
session_start();
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

$response = array('status' => '', 'message' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $email = $conn->real_escape_string($email);
        $sql = "SELECT * FROM user WHERE email='$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                $response['status'] = 'success';
                $response['message'] = 'Login successful.';
                $response['role'] = $user['role'];
                $response['userId'] = $user['userid']; 
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Incorrect email or password.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'User not found.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Please fill in all the fields.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
