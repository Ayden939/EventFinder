<?php
  if($_SESSION['loggedin'] == 'TRUE'){
    $status="Log out";
    $ref = 'controller.php?page=logout';
  }else{
    $status="Login";
    $ref = 'controller.php?page=login';
  }
?>

<header>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand">Event Finder</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="controller.php?page=home">Home</a>
                    </li>
                    <?php if($status == "Log out"): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="controller.php?page=bookmarks" >Bookmarks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="controller.php?page=userinfo">User Portal</a>
                    </li>
                <?php endif; ?>
                <?php if($status == "Login"): ?>
                        <li class="nav-item">
                            <a class="btn btn-secondary my-2 my-sm-0 ms-3" href="controller.php?page=userReg" >Register</a>
                        </li>
                <?php endif; ?>
                <li class="nav-item ms-2">
                    <a class="btn btn-secondary my-2 my-sm-0" style="margin-right: 15px;" href="<?php echo $ref; ?>"><?php echo $status; ?></a>
                </li>
                <li class="nav-item">
                    <a href="controller.php?page=userguide" class="nav-link">
                        <i class="bi bi-question-circle" style="font-size: 1.5rem;"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
</header>
