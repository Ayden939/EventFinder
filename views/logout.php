<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                        <h5 class="card-title">Log Out</h5>
                        <p class="card-text">Are you sure you want to log out?</p>
                        <form action="controller.php" method="POST">
                            <input type="hidden" name="page" value="logout">
                            <button class="btn btn-primary" type="submit" name="submit" value="CONFIRM" >Confirm</button>
                            <button class="btn btn-primary" type="submit" name="submit" value="CANCEL" >Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

