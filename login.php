<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "test");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get input from the Android app (email and password)
$email = $_POST['email'];
$password = $_POST['password'];

// Query the database to check if email and password match
$query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Login success
    echo json_encode(["status" => "success", "message" => "Login successful"]);
} else {
    // Login failed
    echo json_encode(["status" => "fail", "message" => "Invalid email or password"]);
}

$conn->close();
?>
