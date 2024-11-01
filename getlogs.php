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
    if ($user_type == "Student") {
        // Assuming you want to get logs for students
        $query = "SELECT l.date, l.time_in, l.time_out FROM history l JOIN students s ON l.owner_id = s.student_number WHERE s.student_number = '".$user_id."' ORDER BY l.date DESC";
    } else {
        // For other user types
        $query = "SELECT l.date, l.time_in, l.time_out FROM history l JOIN employees e ON l.owner_id = e.employee_number WHERE e.employee_number = '".$user_id."' ORDER BY l.date DESC";
    }

    // Prepare and execute the statement
    // $stmt = $conn->prepare($query);
    // $stmt->bind_param("s", $user_id);
    // $stmt->execute();
    // $result = $stmt->get_result();
    $result = $conn->query($query);

    if ($result) {
        $logs = array();
        
        while ($row = $result->fetch_assoc()) {
            // Format the timestamp
            //$date = new DateTime($row['date']);
            
            $logs[] = [
                "date" => $row['date'],
                "time_in" => $row['time_in'],
                "time_out" => $row['time_out']
            ];
        }

        // Return success response with logs
        echo json_encode([
            "status" => "success",
            "logs" => $logs
        ]);
    } else {
        // Query failed
        echo json_encode([
            "status" => "error",
            "query" => $query,
            "message" => "Query failed: " . $conn->error
        ]);
    }

} catch (Exception $e) {
    // Handle any errors
    echo json_encode([
        "status" => "error",
        "message" => "An error occurred: " . $e->getMessage()
    ]);
}

// Close connections
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>