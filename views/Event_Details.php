    <style>
        .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        #event-details {
            padding: 20px;
        }

        #map {
            width: 100%;
            height: 400px;
            margin-top: 20px;
            border: 1px solid #ccc;
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

            $event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
            //echo "Event ID: " . $event_id . "<br>";

            $sql = "SELECT * FROM events WHERE event_id = ?;";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<h1>" . htmlspecialchars($row["title"]) . "</h1>";
                echo "<p><strong>Description:</strong> " . htmlspecialchars($row["descr"]) . "</p>";
                $date = new DateTime($row["event_date"]);
                $formattedDate = $date->format('F j, Y g:i A');  // Format to "Month day, year HH:MM AM/PM"
                echo "<p class='card-text'><strong>Date/Time:</strong> " . htmlspecialchars($formattedDate) . "</p>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($row["event_loc"]) . "</p>";
                echo "<p><strong>Category:</strong> " . $row["category"] . "</p>";
            } else {
                echo "<p>No event found with the given ID.</p>";
            }
            $userID = $row["user_id"];
            $lat=$row['latitude'];
            $long=$row['longitude'];

            $stmt->close();
            $conn->close();
            ?>

        <?php if($_SESSION['loggedin'] == 'TRUE'): ?>
            <a href="controller.php?page=add&event_id=<?php echo $event_id; ?>" class="btn btn-secondary my-2 my-sm-0">Bookmark Event</a>
        <?php endif; ?>
        </div>

        <div id="map">The Map Will Appear Here</div>

    </div>

    <link rel="stylesheet" type="text/css" href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.25.0/maps/maps.css"/>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.25.0/maps/maps-web.min.js"></script>
    <script>
        var KEY = "hVPf26Pk8XkqrieOLO3xGtdtblsaCBai";

        var latitude = <?php echo $lat; ?>;
        var longitude = <?php echo $long; ?>;
        var PLACE = [longitude, latitude];
        // Initialize the map
        var map = tt.map({
            key: KEY,
            container: 'map',
            style: 'https://api.tomtom.com/map/1/style/20.0.0-8/basic_main.json?key=' + KEY,
            center: PLACE,
            zoom: 15
        });

        var marker = new tt.Marker().setLngLat(PLACE).addTo(map);
    </script>
