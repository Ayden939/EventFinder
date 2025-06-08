
    <style>
         .search-events-container {
        display: flex;
        align-items: flex-start;
        }
        #search {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        #events {
            margin-left: 20px;
            flex-grow: 1;
        }
        .form-control, .form-select {
            width: 175px;
            height: 35px;
            padding: 5px 10px;
            font-size: 14px;
            line-height: 1.5;
            box-sizing: border-box;
        }
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
            <div class = "search-events-container">
                <div id = "search">
                    <form id = "search-form" method = "GET">
                        <input type = "hidden" name = "page" value = "home">
                        <div>
                            <label for="search" class="Form-label mt-2">Search: </label>
                            <input type="text" name="search" class="form-control" placeholder="Search Events">
                        </div>
                        <div>
                            <label for="category" class="Form-label mt-2">Category:</label>
                            <select class="form-select" name="category">
                                <option value="" disabled selected>Select a Category</option>
                                <option value="Family Friendly">Family Friendly</option>
                                <option value="21 & older">21 & older</option>
                                <option value="Outdoor">Outdoor</option>
                                <option value="Indoor">Indoor</option>
                                <option value="Food">Food</option>
                                <option value="Sports">Sports</option>
                                <option value="Music">Music</option>
                                <option value="Free">Free</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="event_date" class="Form-label mt-2">Event Date: </label>
                            <input type="date" name="event_date" id = "event_date" class="form-control">
                        </div>
                        <div>
                            <label for = "radius" class = "Form-label mt-2">Radius: </label>
                            <select class="form-select" name="radius" style="margin-bottom: 15px;">
                                <option value="" disabled selected>Select radius</option>
                                <option value="10">10 miles</option>
                                <option value="20">20 miles</option>
                                <option value="30">30 miles</option>
                                <option value="40">40 miles</option>
                                <option value="50">50 miles</option>
                                <option value="60">60 miles</option>
                                <option value="70">70 miles</option>
                                <option value="80">80 miles</option>
                                <option value="90">90 miles</option>
                                <option value="100">100 miles</option>
                            </select>
                            <input type = "hidden" name = "latitude" id = "latitude">
                            <input type = "hidden" name = "longitude" id = "longitude">
                        </div>  
                        <button type = "button" class="btn btn-secondary my-2 my-sm-0" onClick = "getLocation()">Search</button>
                    </form>
                </div>
            <div class = "event" id = "events">
                <?php
                $servername = "localhost";
                $user = "student";
                $pass = "UApass50";
                $db = "EventFinderDB";

                $conn = new mysqli($servername, $user, $pass, $db);
                if($conn -> connect_error){
                    die("Connection failed: " . $conn -> connect_error);
                }

                // Grab the values to search/filter
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $category = isset($_GET['category']) ? $_GET['category'] : '';
                $event_date = isset($_GET['event_date']) ? $_GET['event_date'] : '';
                $latitude = isset($_GET['latitude']) ? $_GET['latitude'] : '';
                $longitude = isset($_GET['longitude']) ? $_GET['longitude'] : '';
                $radius = isset($_GET['radius']) ? $_GET['radius'] : '';
                $sql = "Select event_id, title, descr, event_date, event_loc, category FROM events
                WHERE 1=1";

                // Create a place to hold binding parameters
                $params = [];
                $types = '';
                // If a search term, category, or date have been provided, append the appropriate condition to the SQL query
                // and the binding parameters to the array and string
                if ($search) {
                    $sql .= " AND (title LIKE ? OR descr LIKE ?)";
                    $searchBy = "%" . $search . "%";
                    $params[] = $searchBy;
                    $params[] = $searchBy;
                    $types .= 'ss';
                }
                if ($category) {
                    $sql .= " AND category = ?";
                    $params[] = $category;
                    $types .= 's';
                }
                if ($event_date) {
                    $sql .= " AND DATE(event_date) = ?";
                    $params[] = $event_date;
                    $types .= 's';
                }
                // Calculate the distance and see if it falls within the radius that the user specified
                // This is done using the Haversine Formula where 3959 is the radius of the earth in miles, can change this value to 6371 if measuring in kilometers
                if ($latitude && $longitude && $radius) {
                    $sql .= " AND (3959 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?";
                    $params[] = $latitude;
                    $params[] = $longitude;
                    $params[] = $latitude;
                    $params[] = $radius;
                    $types .= 'dddi';
                }
                $sql .= " ORDER BY event_date DESC;";
                $stmt = $conn->prepare($sql);
                // Check to make sure that the $types is not empty before binding the parameters
                if(!empty($types)){
                    // The ... operator expands out the elements in the $params array and each element is passed as a separate parameter to the method
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $result = $stmt -> get_result();
                // Check if the SQL query returned any results and create a div that acts as a link to the event details page for each row of the results
                if($result -> num_rows > 0){
                    // fetch_assoc() returns the row as an associative array so that the data can be accessed using column names
                    echo "<div class='card-container'>";
                    while($row = $result -> fetch_assoc()) {
                        echo "<div class='card text-white bg-primary mb-3'>";
                        echo "<a href='controller.php?page=event&event_id=" . $row["event_id"] . "' style='text-decoration: none; color: inherit;'>";
                        echo "<h2 class='card-header'>" . htmlspecialchars($row["title"]) . "</h2>";
                        echo "<div class='card-body'>";
                        echo "<p class='card-text'><strong>Description:</strong> " . htmlspecialchars($row["descr"]) . "</p>";
                        $date = new DateTime($row["event_date"]);
                        $formattedDate = $date->format('F j, Y g:i A');  // Format to "Month day, year HH:MM AM/PM"
                        echo "<p class='card-text'><strong>Date/Time:</strong> " . htmlspecialchars($formattedDate) . "</p>";
                        echo "<p class='card-text'><strong>Location:</strong> " . htmlspecialchars($row["event_loc"]) . "</p>";
                        echo "<p class='card-text'><strong>Category:</strong> " . htmlspecialchars($row["category"]) . "</p>";
                        echo "</div>";
                        echo "</a>";
                        echo "</div>";
                    }
                    echo "</div>"; // Close the card container
                }
                else{
                    echo "<p>Your search returned no results.</p>";
                }
                $stmt -> close();
                $conn -> close();
                ?>
        </div>
    </div>
</div>

<script>
// Asks the user for access to their location and if the user accepts, stores the current longitude and latitude and submits the form
function getLocation(){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition( function(position){
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
            document.getElementById('search-form').submit();
        }, function(error){
            console.error('Error occurred during geolocation:', error);
            alert('Unable to fetch location. ' + error.message)
        });
    } else{
        alert('Browser does not support geolocation.')
    }
}
</script>
