<?php
   $user = $_REQUEST['user'];
   if(isset($_REQUEST['message'])){
       echo $_REQUEST['message'];
    }
    $events = $_REQUEST['events'];
?>
    <style>
        input[type="radio"]{
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #6c757d;
            border-radius: 3px;
            display: inline-block;
            position: relative;
            background-color: #fff;
        }
        input[type="radio"]:checked::before {
            content: '';
            width: 12px;
            height: 12px;
            background-color: #6c757d;
            position: absolute;
            top: 2px;
            left: 2px;
            border-radius: 2px;
        }
        input[type="radio"]:hover {
            border-color: #0056b3;
        }
    </style>

    <div id="app" class="container mt-5">
        <div class="card text-white bg-primary mb-3">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body" style="color: white;">
                    <h2 class="card-title" style="color: white;">User Information</h2>
                        <div class="mb-3">
                            <label class="form-label"><strong>Username:</strong></label>
                            <span><?php echo $user->getUsername(); ?></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Name:</strong></label>
                            <span><?php echo $user->getFirstname(); ?><?php echo ' '; ?><?php echo $user->getLastname(); ?></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Email:</strong></label>
                            <span><?php echo $user->getEmail(); ?></span>
                        </div>

                        <div class="d-flex gap-3">
                            <a class="btn btn-secondary my-2 my-sm-0" href="controller.php?page=update">Update User Information</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="card text-white bg-primary mb-3">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body" style="color: white;">
                        <h2 class="card-title" style="color: white;"><?php echo $user->getFirstname(); ?>'s Events</h2>
                                <div class="col">
                                <form action="controller.php" method="GET">
                                <button class="btn btn-secondary" type="submit" name="page" value="eventReg" style="margin-bottom: 10px;">Event Registration</button>
                                <?php if($events == null): ?>
                                    <p>You do not have any events.</p>
                                <?php endif; ?>
                                <?php if($events != null): ?>
                                <button class="btn btn-secondary" type="submit" name="page" value="eupdate" style="margin-bottom: 10px;">Update Event</button>
                                <button class="btn btn-secondary" type="submit" name="page" value="edelete" style="margin-bottom: 10px;">Delete Event</button>
                                    <table class="table table-bordered table-striped">
                                        <thead><tr><th>Select</th><th>User ID</th><th>Title</th><th>Description</th><th>Event Date</th><th>Event Location</th><th>Category</th></tr></thead>
                                        <tbody>
                                            <?php for ($index = 0; $index < count($events); $index++): ?>
                                            <tr>
                                                <td>
                                                    <input type="radio"
                                                        name="eventID"
                                                        :value="<?php echo $events[$index]->getEventID(); ?>"
                                                        v-model="selectedEventID"
                                                        @click="toggleRadio(<?php echo $events[$index]->getEventID();?>)">
                                                </td>
                                                <td><?php echo $events[$index]->getUserID(); ?></td>
                                                <td><?php echo $events[$index]->getTitle(); ?></td>
                                                <td><?php echo $events[$index]->getDescr(); ?></td>
                                                <td><?php echo $events[$index]->getEventDate(); ?></td>
                                                <td><?php echo $events[$index]->getEventLoc(); ?></td>
                                                <td><?php echo $events[$index]->getCategory(); ?></td>
                                            </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<script>
new Vue({
    el: '#app',
    data: {
        events: <?php echo json_encode($events); ?>, // PHP array converted to JSON for Vue
        selectedEventID: null, // Track the selected radio button value
        lastSelectedID: null   // Track the last selected value to detect double-click
    },
    methods: {
        toggleRadio(eventID) {
            if (this.lastSelectedID === eventID) {
                // If clicked twice on the same radio, deselect it
                this.selectedEventID = null;
            } else {
                // Otherwise, set it as the selected value
                this.selectedEventID = eventID;
            }
            this.lastSelectedID = this.selectedEventID;
        }
    }
});
</script>
