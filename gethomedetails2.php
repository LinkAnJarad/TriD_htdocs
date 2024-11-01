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

// $user_type = "Employee";
// $user_id = "0";

try {
    // Prepare statements to prevent SQL injection
    $query_logs_and_user_info = "SELECT 
        history.date, history.time_in, history.time_out, history.qr,
        employees.first_name, employees.middle_name, employees.last_name,
        employees.email, employees.telephone, employees.employee_number
        FROM history 
        JOIN employees ON history.owner_id = employees.employee_number 
        WHERE employees.employee_number = ?
        ORDER BY history.date DESC";
        
    $query_vehicles = "SELECT plate_number, color, brand, type, verified 
        FROM vehicles 
        WHERE owner_id = ?";

    // Prepare and execute logs and user info query
    $stmt_logs = $conn->prepare($query_logs_and_user_info);
    $stmt_logs->bind_param("s", $user_id);
    $stmt_logs->execute();
    $result_logs = $stmt_logs->get_result();

    // Prepare and execute vehicles query
    $stmt_vehicles = $conn->prepare($query_vehicles);
    $stmt_vehicles->bind_param("s", $user_id);
    $stmt_vehicles->execute();
    $result_vehicles = $stmt_vehicles->get_result();

    // Initialize arrays to store results
    $logs = [];
    $vehicles = [];
    $user_info = null;

    // Process logs and user info
    while ($row = $result_logs->fetch_assoc()) {
        // Store user info only once
        if ($user_info === null) {
            $user_info = [
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'telephone' => $row['telephone'],
                'employee_number' => $row['employee_number']
            ];
        }

        // Store log entry
        $logs[] = [
            'date' => $row['date'],
            'time_in' => $row['time_in'],
            'time_out' => $row['time_out'],
            'qr' => $row['qr']
        ];
    }

    // Process vehicles
    while ($row = $result_vehicles->fetch_assoc()) {
        $vehicles[] = [
            'plate_number' => $row['plate_number'],
            'color' => $row['color'],
            'brand' => $row['brand'],
            'type' => $row['type'],
            'verified' => $row['verified']
        ];
    }

    // Prepare response
    $response = [
        'status' => 'success',
        'data' => [
            'user_info' => $user_info,
            'logs' => $logs,
            'vehicles' => $vehicles
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    // Close prepared statements
    if (isset($stmt_logs)) $stmt_logs->close();
    if (isset($stmt_vehicles)) $stmt_vehicles->close();
    
    // Close database connection
    $conn->close();
}
?>