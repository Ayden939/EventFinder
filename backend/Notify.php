<?php
/*
This script checks the notifications table and sends notifications based on the notify_date column in the bookmarks table.
You can set up a cronjob to run this task periodically.
*/
$hostname = 'localhost';
$username = 'student';
$password = 'UApass50';
$db = 'EventFinderDB';

// Connect to the database.
$conn = new mysqli($hostname, $username, $password, $db);

if ($conn -> connect_error) {
    die("Connection failed: " . $conn -> connect_error);
}


$sql = "SELECT b.user_id, b.event_id, b.notify_date, u.email, u.first_name, u.last_name, e.title, e.event_date
        FROM bookmarks b
        JOIN users u ON b.user_id = u.user_id
        JOIN events e ON b.event_id = e.event_id
        WHERE DATE(b.notify_date) = CURDATE() AND b.is_notified IS NULL;";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// If the SQL query returns any results, send notifications to each user.
if ($result -> num_rows > 0) {
    while ( $row = $result -> fetch_assoc()) {
        $to = $row['email'];
        $subject = "Reminder for bookmarked event: " . $row['title'];
        $message = "Hello, " . $row['first_name'] . " " . $row['last_name'] . "!\n\n";
        $message .= "An event that you have bookmarked is happening soon.\n";
        $message .= "Event: " . $row['title'] . "\n";
        $message .= "Event Date: " . $row['event_date'] . "\n";
        $header = "From: no-reply@eventfinder.com";
        
        //Using the PHP mail() function to deliver the notifications.
        if (mail($to, $subject, $message, $header)) {
            echo "Notification delivered to: " . $to;
        }

        //Change the is_notified column from NULL to 1 to signal that the notification has been sent.
        $notified = "UPDATE bookmarks SET is_notified = 1 WHERE user_id = ? AND event_id = ?;";
        $updateStmt = $conn->prepare($notified);
        $updateStmt->bind_param("ii", $row['user_id'], $row['event_id']);
        $updateStmt->execute();
        $updateStmt -> close();
    }
} else {
    echo "No notifications to deliver.";
}
$stmt -> close();
$conn -> close();
?>