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
                                        <th>Cleared Quiz</th> <!-- New table header -->
                                        <th>View Attempts</th>
                                    </tr>
                                </thead>
                                <tbody id="user_table">
                                    <?php
                                    // Fetch user data, current day streak, and cleared quiz count from the database
                                    $userStreakQuery = "SELECT u.id, u.name, u.email, IFNULL(s.total_days, 0) AS total_days, COUNT(l.user_id) AS cleared_quiz 
                                                        FROM user u 
                                                        LEFT JOIN user_streak s ON u.id = s.user_id 
                                                        LEFT JOIN lesson_unlocks l ON u.id = l.user_id";
                                    $userStreakQuery .= " GROUP BY u.id"; // Grouping to count cleared quizzes per user
                                    $userStreakResult = mysqli_query($con, $userStreakQuery);

                                    if ($userStreakResult) {
                                        while ($user = mysqli_fetch_assoc($userStreakResult)) {
                                            echo "<tr>";
                                            echo "<td>{$user['id']}</td>";
                                            echo "<td>{$user['name']}</td>";
                                            echo "<td>{$user['email']}</td>";
                                            echo "<td>";
                                            // Check if the streak is zero
                                            if ($user['total_days'] == 0) {
                                                echo "0";
                                            } else {
                                                echo $user['total_days']; // Display streak value
                                            }
                                            echo "</td>";
                                            echo "<td>{$user['cleared_quiz']}</td>"; // Display cleared quiz count
                                            echo "<td><button class='btn btn-info btn-sm'  style='width: 100px;' onclick='viewAttempts({$user['id']})'><i class='bi bi-person-lines-fill fs-6'></i></button></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>Error fetching user data, streak, and cleared quiz count.</td></tr>";
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

    <!-- Modal to display attempts -->
    <div class="modal fade" id="attemptsModal" tabindex="-1" role="dialog" aria-labelledby="attemptsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attemptsModalLabel">User Attempts</h5>
                </div>
                <div class="modal-body" id="attemptsContent">
                    <!-- Attempt details will be displayed here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewAttempts(userId) {
            // Clear previous attempt content
            document.getElementById('attemptsContent').innerHTML = '';

            // Fetch attempts for the selected user
            $.ajax({
                url: 'fetch_user_attempts.php',
                type: 'GET',
                data: {
                    userId: userId
                },
                success: function(response) {
                    $('#attemptsContent').html(response);
                    $('#attemptsModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    </script>

</body>

</html>
