<?php
header('Content-Type: application/json');

// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kendal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// Get the input data from the request body
$inputData = json_decode(file_get_contents('php://input'), true);

// Check if description is provided
if (!isset($inputData['description'])) {
    echo json_encode(["success" => false, "message" => "Missing description"]);
    exit();
}

$inputDescription = $inputData['description'];

// Define keywords to search for
$keywords = [
    'Rotor Coil', 'Stator Windings', 'Exciter Diode Bridge', 'Cooling Fan', 
    'Bearing Housing', 'Arc Extinguishing Chamber', 'Contact Mechanism', 
    'Breaker Spring Assembly', 'Trip Coil', 'Insulation Barrier', 
    'Power Semiconductor Module (IGBT)', 'Control Circuit Board', 
    'Capacitor Bank', 'Input Rectifier', 'Circuit Breaker Contacts', 
    'Voltage Transformer', 'Current Transformer', 'Busbars', 
    'Insulating Medium (SF6 Gas)', 'Bearings', 'Rotor Bars', 
    'Shaft Coupling'
];

// Find matching keywords in the input description
$matchedKeywords = [];
foreach ($keywords as $keyword) {
    if (stripos($inputDescription, $keyword) !== false) {
        $matchedKeywords[] = $keyword;
    }
}

// Prepare SQL statement to retrieve matching records
if (!empty($matchedKeywords)) {
    // Use the first matched keyword for the SQL query
    $keyword = $matchedKeywords[0]; 
    $sql = "SELECT * FROM shared_knowledge WHERE part_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $keyword);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        
        if ($data) {
            echo json_encode(["success" => true, "data" => $data]);
        } else {
            echo json_encode(["success" => false, "message" => "No matching records found."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error executing query: " . $stmt->error]);
    }

    // Close statement
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "No keywords found."]);
}

// Close connection
$conn->close();
?>
