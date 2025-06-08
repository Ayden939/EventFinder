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
    die('Connection failed: ' . $conn->connect_error);
}

function locate($address){
    $apiKey = 'hVPf26Pk8XkqrieOLO3xGtdtblsaCBai'; 
    $formatted = urlencode($address);
    $url = "https://api.tomtom.com/search/2/geocode/{$formatted}.json?key={$apiKey}";

    $tomReply = file_get_contents($url);
    $info = json_decode($tomReply);

    // Check if results exist
    if (empty($info->results)) {
        return null; // No results found
    } else {
        // Retrieve latitude and longitude
        $latitude = $info->results[0]->position->lat;
        $longitude = $info->results[0]->position->lon;
        return array('latitude' => $latitude, 'longitude' => $longitude);
    }
}
    if ($_SERVER['REQUEST_METHOD'] === "POST"){
        $title = $_POST['title'];
        $descr = $_POST['descr'];
        $event_date = $_POST['event_date'];
        $event_time = $_POST['event_time'];
        $event_loc = $_POST['event_loc'];

        $event_date_time = $event_date . ' ' . $event_time;

        $location = locate($event_loc);
        if ($location == null) {
            die('Location not found');
        }else{
            $latitude = $location['latitude'];
            $longitude = $location['longitude'];
        }
    
        // Prepare the SQL statements
        $stmt = $conn->prepare("INSERT INTO events (title, descr, event_date_time, event_loc, latitude, longitude ) VALUES (?, ?, ?, ?, ?, ?)");
        // Bind
        $stmt->bind_param("ssssdd", $title, $descr, $event_date_time, $event_loc, $latitude, $longitude);
        // Execute
        $stmt->execute();


        echo "Event registered successfully";

        // Close 
        $stmt->close();
        $conn->close();
    }

?>
