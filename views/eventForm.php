<?php
    $eventID = $_GET['eventID'];
    if($eventID!=null){
        $eventDAO = new EventDAO();
        $event = $eventDAO->getEvent($eventID);
    }else{
        header("Location: controller.php?page=perror"); // Redirect to error page if no eventID selected
    }
 ?>

     <style>
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="container mt-5">
        <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card text-white bg-primary mb-3" style="max-width: 700px; width: 100%;">
                <h2 class="card-header">Update Event Details</h2>
                <div class="card-body">
                        <form action="controller.php" method="POST">
                            <input type="hidden" name="page" value="eupdate">
                            <input type="hidden" name="eventID" value="<?php echo $event->getEventID(); ?>">
                            <input type="hidden" name="userID" value="<?php echo $event->getUserID(); ?>">
                            <div>
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title"
                                value="<?php echo $event->getTitle(); ?>" required>
                            </div>
                            <div>
                            <label for="descr" class="form-label" style="margin-top: 15px;">Description</label>
                            <input type="text" class="form-control" id="descr" name="descr" placeholder="Enter Description"
                                value="<?php echo $event->getDescr(); ?>" required>
                            </div>
                            <div>
                            <label for="event_date" class="form-label" style="margin-top: 15px;">Event Date</label>
                            <input type="text" class="form-control" id="event_date" name="event_date" placeholder="Enter the Event Date"
                                value="<?php echo $event->getEventDate(); ?>" >
                            </div>
                            <div>
                            <label for="event_loc" class="form-label" style="margin-top: 15px;">Event Location</label>
                            <input type="text" class="form-control" id="event_loc" name="event_loc" placeholder="Enter the Event Location"
                                value="<?php echo $event->getEventLoc(); ?>" >
                            </div>
                            <div>
                            <label for="category" style="margin-top: 15px;">Category</label>
                            <select class="form-select" name="category">
                                <option value="" disabled>Select a category</option>
                                <option value="Family Friendly" <?php echo ($event->getCategory() == "Family Friendly") ? 'selected' : ''; ?>>Family Friendly</option>
                                <option value="21 & older" <?php echo ($event->getCategory() == "21 & older") ? 'selected' : ''; ?>>21 & older</option>
                                <option value="Outdoor" <?php echo ($event->getCategory() == "Outdoor") ? 'selected' : ''; ?>>Outdoor</option>
                                <option value="Indoor" <?php echo ($event->getCategory() == "Indoor") ? 'selected' : ''; ?>>Indoor</option>
                                <option value="Food" <?php echo ($event->getCategory() == "Food") ? 'selected' : ''; ?>>Food</option>
                                <option value="Sports" <?php echo ($event->getCategory() == "Sports") ? 'selected' : ''; ?>>Sports</option>
                                <option value="Music" <?php echo ($event->getCategory() == "Music") ? 'selected' : ''; ?>>Music</option>
                                <option value="Free" <?php echo ($event->getCategory() == "Free") ? 'selected' : ''; ?>>Free</option>
                                <option value="Other" <?php echo ($event->getCategory() == "Other") ? 'selected' : ''; ?>>Other</option>
                            </select>
                            </div>
                            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
