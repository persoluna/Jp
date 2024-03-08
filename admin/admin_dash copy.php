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


<!-- Temp Work --><br>
<div style="width: 100%;">



    <div style="position: relative; width: 180px; height: 130px; background-image: linear-gradient(45deg, black, #126dfd); border-radius: 12px; border: 1px solid black; padding: 5px; margin: 5px;">
        <img src="" style="width: 100px; height: 100px;"/>
        <span style="position: absolute; font-size: 18px; color: white; bottom: 5px; left: 5px;">Dashboard</span>
        </div>    

    <div style="position: relative; width: 180px; height: 130px; background-image: linear-gradient(45deg, black, #00ff04); border-radius: 12px; border: 1px solid black; padding: 5px; margin: 5px;">
        <img src="" style="width: 100px; height: 100px;"/>
        <span style="position: absolute; font-size: 18px; color: white; bottom: 5px; left: 5px;">Dashboard</span>
        </div>

    <div style="position: relative; width: 180px; height: 130px; background-image: linear-gradient(45deg, black, #c2ab00); border-radius: 12px; border: 1px solid black; padding: 5px; margin: 5px;">
        <img src="" style="width: 100px; height: 100px;"/>
        <span style="position: absolute; font-size: 18px; color: white; bottom: 5px; left: 5px;">Dashboard</span>
        </div>



</div>

</body>



</html>