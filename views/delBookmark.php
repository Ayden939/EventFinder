<?php
    $eventID = $_GET['event_id'];
    if($eventID!=null){

    }else{
        header("Location: controller.php?page=perror"); // Redirect to error page if no eventID selected
    }
 ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Remove Bookmark</h5>
                    <p class="card-text">Confirm Deletion of Bookmark <?php echo $eventID; ?> from the list.</p>
                    <form action="controller.php" method="POST">
                        <input type="hidden" name="page" value="bdelete">
                        <input type="hidden" name="eventID" value="<?php echo $eventID; ?>">
                        <button class="btn btn-primary" type="submit" name="submit" value="CONFIRM">Confirm</button>
                        <button class="btn btn-primary" type="submit" name="submit" value="CANCEL">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

