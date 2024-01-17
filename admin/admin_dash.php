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
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-9 col-lg-10 px-md-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Users</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Current Day Streak</th>
                                    </tr>
                                </thead>
                                <tbody id="user_table">
                                    <?php
                                    // Fetch user data and current day streak from the database
                                    $userStreakQuery = "SELECT u.id, u.name, u.email, s.total_days 
                                                        FROM user u 
                                                        LEFT JOIN user_streak s ON u.id = s.user_id AND s.last_completed_date = CURDATE()";
                                    $userStreakResult = mysqli_query($con, $userStreakQuery);

                                    if ($userStreakResult) {
                                        while ($user = mysqli_fetch_assoc($userStreakResult)) {
                                            echo "<tr>";
                                            echo "<td>{$user['id']}</td>";
                                            echo "<td>{$user['name']}</td>";
                                            echo "<td>{$user['email']}</td>";
                                            echo "<td>{$user['total_days']}</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>Error fetching user data and streak.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>