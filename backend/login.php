<?php
// Configuration for MariaDB connection
$host = 'localhost';
$db = 'EventFinderDB';
$user = 'student';
$pass = 'UApass50';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_REQUEST['UserLogin'])) {

    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    // Check for empty username or password
    if (empty($username)) {
        echo "Username is required";
    } else if (empty($password)) {
        echo "Password is required";
    } else {
        // Prepare the SQL query to get the hashed password
        $query = "SELECT pass_hash FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($query)) {

            $stmt->bind_param("s", $username);

            // Execute the query
            if ($stmt->execute()) {

                $stmt->store_result();

                // Check if the user exists
                if ($stmt->num_rows > 0) {

                    $stmt->bind_result($hashed_password);
                    $stmt->fetch();

                    // Verify the input password with the hashed password
                    if (password_verify($password, $hashed_password)) {

                        $_SESSION["username"] = $username;

                        echo "Login successful! Welcome, " . $_SESSION["username"];

                        // Optionally, redirect to a dashboard page
                         header("Location: Event_Page.php");
                         exit();
                    } else {

                        echo "Invalid username or password.";
                    }
                } else {

                    echo "Invalid username or password.";
                }


                $stmt->close();
            } else {
                echo "Error executing query.";
            }
        } else {
            echo "Error preparing statement.";
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>
