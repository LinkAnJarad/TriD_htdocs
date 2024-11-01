<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "trid");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get input from the Android app (email and password)


$guest_name = $_POST['guest_name'];
$guest_platenumber = $_POST['guest_platenumber'];
$guest_email = $_POST['guest_email'];
$guard_notes = $_POST['guard_notes'];
$guest_vehicletype = $_POST['guest_vehicletype'];
$idtype = $_POST['idtype'];
$reason = $_POST['reason'];
$guest_ID_base64 = $_POST['guest_ID_base64'];

//echo json_encode(["status" => "success"]);
//$result = $conn->query($query);

$query = "INSERT INTO guests (full_name, email, id_image, reason, guard_notes, plate_number, id_type, vehicle_type) VALUES ('".$guest_name."', '".$guest_email."', '".$guest_ID_base64."', '".$reason."', '".$guard_notes."', '".$guest_platenumber."', '".$idtype."', '".$guest_vehicletype."')";
$result = $conn->query($query);

if ($result) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "fail"]);
}


//$result = $conn->query($query);

// if ($result->num_rows > 0) {
//     // Login success
//     echo json_encode(["status" => "test"]);
// } else {
//     // Login failed
//     echo json_encode(["status" => "fail"]);
// }

$conn->close();
?>
