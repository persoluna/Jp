<?php
//  session_start();
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="home.php">
            <img src="assets/logo.png" alt="Bootstrap" width="40" height="34">
        </a>
    </div>
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <?php
                // Check if the user is logged in
                if (isset($_SESSION['user_id'])) {
                    // User is logged in, display other links
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="chapters.php">Chapters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="favorite_chapters.php">Favorite Chapters</a>
                    </li>
                <?php
                } else {
                    // User is not logged in, display the Login and Register links
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
