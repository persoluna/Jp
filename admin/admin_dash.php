<?php
session_start();
include("include/header.php");
include("../config/db.php");
include("include/sidebar.php");

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_redirect_message'] = 'You need to log in to access the dashboard';
    header("location: login.php");
    exit();
}

?>

<body>
    <br>
    <hr>
    <br>
    <div class="container">
        <div class="row d-flex flex-wrap justify-content-center align-items-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <a href="user_details.php" class="text-decoration-none text-white">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <i class="bi bi-person" style="font-size: 3.5rem;"></i>
                        </div>
                        <div class="card-footer mt-4">
                            <h5 class="card-title mb-1">User Details</h5>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <a href="attempt_details.php" class="text-decoration-none text-white">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <i class="bi bi-journal-check" style="font-size: 3.5rem;"></i>
                        </div>
                        <div class="card-footer mt-4">
                            <h5 class="card-title mb-1">Attempts Details</h5>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-secondary text-white">
                    <a href="quiz_detail.php" class="text-decoration-none text-white">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <i class="bi bi-patch-question-fill" style="font-size: 3.5rem;"></i>
                        </div>
                        <div class="card-footer mt-4">
                            <h5 class="card-title mb-1">Quiz Details</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>



</html>