<?php
    $userID = $_SESSION['userID'];
    if(!$userID){
        header("Location: controller.php?page=perror");
        exit;
    }
?>

<style>
    .card-container {
        display: flex;                   /* Use flexbox */
        flex-wrap: wrap;                /* Allow items to wrap */
        justify-content: space-between;  /* Evenly space cards in each row */
        margin-top: 20px;               /* Optional margin from the top */
    }

    .card {
        flex: 0 0 calc(50% - 10px);    /* Set cards to take up half of the container width minus gap */
        margin-bottom: 20px;            /* Space between rows */
        max-width: 100%;                /* Ensure cards fill their container */
    }
</style>

<div class="container mt-5">
    <div id="event-details">
        <?php
        $servername = "localhost";
        $user = "student";
        $pass = "UApass50";
        $db = "EventFinderDB";

        $conn = new mysqli($servername, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT e.event_id, e.title, e.descr, e.event_date, e.event_loc
                FROM bookmarks b
                JOIN events e ON b.event_id = e.event_id
                WHERE b.user_id = ?;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<div class='card-container'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card text-white bg-primary mb-3' style='max-width: 70rem;'>";
                echo "<h2 class='card-header'>" . htmlspecialchars($row["title"]) . "</h2>";
                echo "<div class='card-body'>";
                echo "<p class='card-text'><strong>Description:</strong> " . htmlspecialchars($row["descr"]) . "</p>";
                $date = new DateTime($row["event_date"]);
                $formattedDate = $date->format('F j, Y g:i A');  // Format to "Month day, year HH:MM AM/PM"
                echo "<p class='card-text'><strong>Date/Time:</strong> " . htmlspecialchars($formattedDate) . "</p>";
                echo "<p class='card-text'><strong>Location:</strong> " . htmlspecialchars($row["event_loc"]) . "</p>";
                echo "<a href='controller.php?page=bdelete&event_id=" . $row["event_id"] . "' class='btn btn-secondary my-2 my-sm-0'>Delete Bookmark</a>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No bookmarks to show.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>
</div>
