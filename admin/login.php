<?php
session_start();
include("../config/db.php");
include("emailFunctions.php");

// Function to check and update user streak
function checkAndUpdateStreak($con, $userId)
{
    // Get today's date
    $todayDate = date('Y-m-d');

    // Check if there's an entry for today
    $checkStreakTodayQuery = "SELECT * FROM user_streak WHERE user_id = $userId AND last_completed_date = '$todayDate'";
    $streakTodayResult = mysqli_query($con, $checkStreakTodayQuery);

    // Check if there's an entry for yesterday
    $yesterdayDate = date('Y-m-d', strtotime('-1 day'));
    $checkStreakYesterdayQuery = "SELECT * FROM user_streak WHERE user_id = $userId AND last_completed_date = '$yesterdayDate'";
    $streakYesterdayResult = mysqli_query($con, $checkStreakYesterdayQuery);

    if (mysqli_num_rows($streakTodayResult) == 0 && mysqli_num_rows($streakYesterdayResult) == 0) {
        // If no entry for today and yesterday, update the streak to zero
        $updateStreakQuery = "UPDATE user_streak SET total_days = 0 WHERE user_id = $userId";
        mysqli_query($con, $updateStreakQuery);
    }
}

if (isset($_SESSION['login_redirect_message'])) {
    echo '<div class="alert alert-warning">' . $_SESSION['login_redirect_message'] . '</div>';
    unset($_SESSION['login_redirect_message']);
}

if (isset($_POST['submit'])) {
    extract($_POST);

    $sql = "SELECT id, name FROM admin WHERE email='$email' AND password='$password'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {

        // Login successful
        $row = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_name'] = $row['name'];

        // Check and update streak for all users
        $getAllUsersQuery = "SELECT id FROM user";
        $allUsersResult = mysqli_query($con, $getAllUsersQuery);

        while ($userRow = mysqli_fetch_assoc($allUsersResult)) {
            $userId = $userRow['id'];
            checkAndUpdateStreak($con, $userId);
        }

        // Check for users with XP greater than or equal to 5000 and email not sent
        $xpCheckQuery = "SELECT u.id, u.name, u.email FROM user u JOIN user_xp ux ON u.id = ux.user_id WHERE ux.xp >= 5000 AND ux.email_sent = 0";
        $xpCheckResult = mysqli_query($con, $xpCheckQuery);

        $emailsSentCount = 0;
        // Loop through the result set
        while ($xpRow = mysqli_fetch_assoc($xpCheckResult)) {
            $user_id = $xpRow['id'];
            $user_name = $xpRow['name'];
            $user_email = $xpRow['email'];

            // Call a function to send the congratulatory email
            sendCongratulatoryEmail($user_name, $user_email);

            // Update the database flag to indicate the email has been sent
            $updateFlagQuery = "UPDATE user_xp SET email_sent = 1 WHERE user_id = $user_id";
            mysqli_query($con, $updateFlagQuery);

            $emailsSentCount++;
        }
    } else {
        echo "Wrong  username or password";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Japanese Vocabulary</title>
    <link rel="icon" type="image/x-icon" href="include/adminLogin.png">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background: url('../assets/8U8u.gif') fixed center;
            background-size: cover;
        }

        .card {
            background: transparent;
            border: none;
        }

        .card-body {
            background: none;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.5) !important;
            border: 1px solid #ced4da;
        }

        .custom-button-container {
            text-align: center;
            margin-top: 10px;
        }

        .card-title {
            text-shadow: 0 0 3px #000000, 0 0 5px #000000;
        }

        .custom-button {
            background-color: #000000;
            color: #ffffff;
            padding: 7px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .custom-button:hover {
            background-color: #ffffff;
            color: #4b0082;
        }
    </style>

</head>

<body>
    <br>
    <br>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">
                            <strong>ADMIN LOGIN</strong>
                        </h2>
                        <br>
                        <form method="POST">
                            <div class="mb-3">
                                <input type="email" class="form-control" aria-describedby="emailHelp" name="email" placeholder="Enter your Email">
                            </div>
                            <br>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Enter your Password">
                            </div>
                            <br>
                            <div class="custom-button-container">
                                <button type="submit" class="custom-button" name="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="popupModal" tabindex="-1" role="dialog" aria-labelledby="popupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <?php
                    if (isset($_POST['submit']) && $result && mysqli_num_rows($result) > 0) {
                        echo "Gmails have been sent to $emailsSentCount users.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function() {
        try {
            <?php
            if (isset($_POST['submit']) && $result && mysqli_num_rows($result) > 0) {
                // Display popup modal
                echo "$('#popupModal').modal('show');";

                echo "setTimeout(function() {
                    window.location.href = 'admin_dash.php';
                }, 2000);"; // 2 seconds
            }
            ?>
        } catch (error) {
            console.error('An error occurred:', error);
        }
    });
</script>

</html>