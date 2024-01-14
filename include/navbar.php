<nav class="navbar navbar-expand-lg navbar-light bg-lightgreen p-2">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/logo.png" alt="Bootstrap" width="35" height="34">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active-page' : ''; ?>" href="index.php">
                        <i class="bi bi-house-fill"></i>
                        <span>Home</span>
                    </a>
                </li>
                <?php
                if (isset($_SESSION['user_id'])) {
                ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'Quiz.php') ? 'active-page' : ''; ?>" href="Quiz.php"><i class="bi bi-question-circle-fill"></i> Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'SB.php') ? 'active-page' : ''; ?>" href="SB.php"><i class="bi bi-trophy-fill"></i> Score Board</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'favorite_chapters.php') ? 'active-page' : ''; ?>" href="favorite_chapters.php"><i class="bi bi-bookmark-heart-fill"></i> Favorite Chapters</a>
                    </li>
                    <li class="nav-item btn-group text-center">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="uploads/<?= $_SESSION['profile_pic']; ?>" alt="User" width="34" height="34"> <?php echo $_SESSION['user_name']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="dashboard.php"><i class="bi bi-person-fill"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear-fill"></i> Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="post" action="logout.php">
                                    <button type="submit" name="logout" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php
                } else {
                ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'login.php') ? 'active-page' : ''; ?>" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'register.php') ? 'active-page' : ''; ?>" href="register.php"><i class="bi bi-person-plus-fill"></i> Register</a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
<style>
    @import url('https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap');

    .navbar {
        font-family: 'Roboto', sans-serif;
        background-color: white;
        text-align: center;
    }

    .nav-link {
        font-size: 15px;
        font-weight: 500;
        color: black;
        transition: color 0.3s, transform 0.3s;
    }

    .nav-item {
        margin-right: 20px;
        text-align: center;
    }

    .nav-link.active-page {
        background-color: #000000;
        padding: 8px 15px;
        border-radius: 19px;
        color: #fff;
        display: inline-block;
        line-height: 1.5;
        text-align: center;
        place-items: center;
    }

    .navbar-toggler-icon {
        background-color: blue;
    }

    .nav-link i,
    .nav-link img {
        margin-right: 8px;
        padding: 4px;
    }

    .nav-link img {
        border-radius: 50%;
    }

    .nav-link:hover {
        color: #008000;
        transform: scale(1.1);
    }

    .dropdown-menu:hover {
        transform: scale(1.05);
        transition: all 0.3s ease-in-out;
    }

    .dropdown-item:hover {
        color: #008000;
        transition: color 0.3s, transform 0.3s;
    }

    .dropdown-item:hover i {
        transform: scale(1.1);
    }

    .container {
        color: #004d00;
    }
</style>