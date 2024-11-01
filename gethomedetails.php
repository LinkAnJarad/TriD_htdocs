<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "trid");

// Set header to specify JSON response
header('Content-Type: application/json');

if ($conn->connect_error) {
    die(json_encode([
        "status" => "error",
        "message" => "Connection failed: " . $conn->connect_error
    ]));
}

$user_type = $_POST['user_type'];
$user_id = $_POST['user_id'];

try {
    $query_user = "SELECT * FROM history JOIN employees on history.owner_id = employees.employee_number WHERE employees.employee_number = '".$user_id."' ORDER BY history.date DESC";
    $query_vehicles = "SELECT * FROM vehicles WHERE owner_id = '".$user_id."'";

    $user_result = $conn->query($query_user);
    $user_info = $user_result->fetch_assoc();

    // Execute vehicles query
    $vehicles_result = $conn->query($query_vehicles);
    $vehicles = [];
    while ($vehicle = $vehicles_result->fetch_assoc()) {
        $vehicles[] = $vehicle;
    }


    // Combine all data
    $response = [
        "status" => "success",
        "data" => [
            "user_info" => $user_info ?? null,
            "vehicles" => $vehicles,
        ]
    ];

    echo json_encode($response, JSON_PRETTY_PRINT);


} catch (Exception $e) {
    // Handle any errors
    echo json_encode([
        "status" => "error",
        "message" => "An error occurred: " . $e->getMessage()
    ]);
}

// Close connections
if (isset($result)) {
    $result->close();
}
$conn->close();
?>