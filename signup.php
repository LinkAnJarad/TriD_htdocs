<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "trid");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get input from the Android app (email and password)


$first_name = $_POST['first_name'];
$middle_name = $_POST['middle_name'];
$last_name = $_POST['last_name'];
$telephone = $_POST['telephone_number'];
$address = $_POST['address'];
$gender = $_POST['gender'];
$user_type = $_POST['user_type'];
$email = $_POST['email'];
$password = $_POST['password'];

if ($user_type == "Student") {
    $student_id_number = $_POST['student_id_number'];
    $college = $_POST['college'];
    $student_id_base64 = $_POST['student_id_base64'];
} else if ($user_type = "Employee") {
    $employee_id_number = $_POST['employee_id_number'];
    $employee_id_type = $_POST['employee_id_type'];
    $employee_id_base64 = $_POST['employee_id_base64'];
}

$update_user_success = FALSE;
$update_vehicle_success = FALSE;

$tableCarsJson = $_POST['tableCarsJson'];

//echo json_encode(["status" => "success"]);

if ($user_type == "Student") {
    $query = "INSERT INTO Students (student_number, first_name, middle_name, last_name, password, gender, email_verified, student_id_image, address, telephone, college_department) VALUES ('".$student_id_number."', '".$first_name."', '".$middle_name."', '".$last_name."', '".$password."', '".$gender."', 'Unverified', '".$student_id_base64."', '".$address."', '".$telephone."', '".$college."')";

    $result = $conn->query($query);
    

    if ($result) {
        // Success
        //echo json_encode(["status" => "success"]);
        $update_user_success = TRUE;
    }

        // Decode the JSON string into a PHP array
    $jsonCars = json_decode($tableCarsJson);

    // Iterate over the array
    foreach ($jsonCars as $object) {
        $plate = $object->column1;
        $type = $object->column2;
        $brand = $object->column1;
        $color = $object->column2;
        $query = "INSERT INTO Vehicles (plate_number, color, owner_id, brand, type, verified) VALUES ('".$plate."', '".$color."', '".$employee_id_number."', '".$brand."', '".$type."', 'Unverified')";
        
        $result = $conn->query($query);

        if ($result) {
            // Success
            //echo json_encode(["status" => "success"]);
            $update_vehicle_success = TRUE;
        }

    }

} else {
    $query = "INSERT INTO Employees (employee_number, first_name, middle_name, last_name, email, gender, email_verified, password, employee_id_type, employee_id_image, address, telephone) VALUES ('".$employee_id_number."', '".$first_name."', '".$middle_name."', '".$last_name."', '".$email."', '".$gender."', 'Unverified', '".$password."', '".$employee_id_type."', '".$employee_id_base64."', '".$address."', '".$telephone."')";

    $result = $conn->query($query);
    
    if ($result) {
        // Success
        //echo json_encode(["status" => "success"]);
        $update_user_success = TRUE;
    }

        // Decode the JSON string into a PHP array
    $jsonCars = json_decode($tableCarsJson);

    // Iterate over the array
    foreach ($jsonCars as $object) {
        $plate = $object->column1;
        $type = $object->column2;
        $brand = $object->column1;
        $color = $object->column2;
        $query = "INSERT INTO Vehicles (plate_number, color, owner_id, brand, type, verified) VALUES ('".$plate."', '".$color."', '".$employee_id_number."', '".$brand."', '".$type."', 'Unverified')";
        
        $result = $conn->query($query);

        if ($result) {
            // Success
            //echo json_encode(["status" => "success"]);
            $update_vehicle_success = TRUE;
        }

    }
}



if ($update_user_success && $update_vehicle_success) {
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
