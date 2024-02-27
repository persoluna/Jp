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
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-primary text-center text-white" style="height: 200px; width: 350px;">
                    <a href="user_details.php" class="text-decoration-none text-dark">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <i class="bi bi-person" style="font-size: 3.5rem;"></i>
                        </div>
                        <h5 class="card-title">User Details</h5>
                </div>
                </a>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-center text-white" style="height: 200px; width: 350px;">
                    <a href="attempt_details.php" class="text-decoration-none text-dark">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <i class="bi bi-journal-check" style="font-size: 3.5rem;"></i>
                        </div>
                        <h5 class="card-title">Attempts Details</h5>
                </div>
                </a>
            </div>
        </div>
    </div>
</body>


</html>