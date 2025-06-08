<?php
// Configuration for MariaDB connection
$host = 'localhost'; 
$db = 'EventFinderDB';
$user = 'student';
$pass = 'UApass50';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    echo $username;

    // make sure nothing is emtpy
    if (empty($username) || empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "All fields are required!"]);
        exit;
    }

    // Hash the password for securitys
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, first_name, last_name, email, pass_hash) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }else{
            $stmt->bind_param("sssss", $username, $firstName, $lastName, $email, $hashedPassword); // Binding parameters
    }

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User successfully registered!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
