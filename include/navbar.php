<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/gon.gif" alt="Bootstrap" width="55" height="34">
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
                    // User is logged in, display other links and the dropdown
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="chapters.php">Chapters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="favorite_chapters.php">Favorite Chapters</a>
                    </li>
                    <!-- Use the btn-group and text-center classes to center the dropdown toggle and menu -->
                    <li class="nav-item btn-group text-center">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['user_name']; ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="dashboard.php">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="post" action="logout.php">
                                    <button type="submit" name="logout" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
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
