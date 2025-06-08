 <?php
    $userID = $_SESSION['userID'];
    if($userID!=null){
        $userDAO = new UserDAO();
        $user = $userDAO->getUser($userID);
    }else{
        header("Location: controller.php?page=home");
    }
 ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card text-white bg-primary mb-3" style="max-width: 700px; width: 100%;">
                <h2 class="card-header">Update User Information</h2>
                <div class="card-body">
                        <form action="controller.php" method="POST">
                            <input type="hidden" name="page" value="update">
                            <input type="hidden" name="userID" value="<?php echo $user->getUserID(); ?>">
                            <div>
                                <label for="lastname" class="form-label">Last Name: </label>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name"
                                    value="<?php echo $user->getLastname(); ?>" required>
                            </div>
                            <div>
                                <label for="firstname" class="form-label" style="margin-top: 15px;">First Name: </label>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name"
                                    value="<?php echo $user->getFirstname(); ?>" required>
                            </div>
                            <div>
                                <label for="username" class="form-label" style="margin-top: 15px;">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your Username"
                                    value="<?php echo $user->getUsername(); ?>" required>
                            </div>
                            <div>
                                <label for="email" class="form-label" style="margin-top: 15px;">Email:</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your Email Address"
                                    value="<?php echo $user->getEmail(); ?>" required>
                            </div>
                            <div>
                                <label for="passwd" class="form-label" style="margin-top: 15px;">Password:</label>
                                <input type="password" class="form-control" id="passwd" name="passwd" placeholder="Enter your Password" required>
                            </div>
                            <div>
                                <label for="confirmpasswd" class="form-label" style="margin-top: 15px;">Confirm Password:</label>
                                <input type="password" class="form-control" id="confirmpasswd" name="confirmpasswd" placeholder="Confirm your Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
