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
    $query = "SELECT l.date, l.plate_number, l.slot_number, l.qr, l.time_out, v.type FROM history l JOIN vehicles v ON l.plate_number = v.plate_number WHERE l.owner_id = '".$user_id."' ORDER BY l.date DESC";

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

            $time_out = $row['time_out'];
            if (!isset($time_out)) {
                $time_out = '';
            }
            
            $history[] = [
                "date" => $row['date'],
                "time_out" => $time_out,
                "plate_number" => $row['plate_number'],
                "slot_number" => $row['slot_number'],
                "type" => $row['type'],
                "qr" => $row['qr']
            ];
        }

        // Return success response with history
        echo json_encode([
            "status" => "success",
            "history" => $history
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
if (isset($result)) {
    $result->close();
}
$conn->close();
?>