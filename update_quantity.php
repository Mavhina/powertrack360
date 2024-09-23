<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(array("success" => false, "message" => "Connection failed: " . $conn->connect_error));
    exit();
}

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Extract Part_Name and Quantity from JSON input
$part_name = isset($data['Part_Name']) ? $data['Part_Name'] : '';
$quantity_to_subtract = isset($data['Quantity']) ? (int)$data['Quantity'] : 0;

if (empty($part_name) || $quantity_to_subtract <= 0) {
    echo json_encode(array("success" => false, "message" => "Invalid input."));
    exit();
}

// Query to get the current Quantity for the given Part_Name
$sql = "SELECT Quantity FROM inventory WHERE Part_Name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $part_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the current quantity
    $row = $result->fetch_assoc();
    $current_quantity = (int)$row['Quantity'];

    // Calculate the new quantity
    $new_quantity = $current_quantity - $quantity_to_subtract;

    // Ensure the new quantity doesn't go below zero
    if ($new_quantity < 0) {
        echo json_encode(array("success" => false, "message" => "Quantity cannot be less than zero."));
        exit();
    }

    // Update the quantity in the database
    $update_sql = "UPDATE inventory SET Quantity = ? WHERE Part_Name = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("is", $new_quantity, $part_name);

    if ($update_stmt->execute()) {
        echo json_encode(array("success" => true, "message" => "Quantity updated successfully.", "new_quantity" => $new_quantity));
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to update quantity."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Part not found."));
}

$stmt->close();
$conn->close();
?>
