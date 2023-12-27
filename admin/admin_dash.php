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
                                        <th>Password</th>
                                    </tr>
                                </thead>
                                <tbody id="user_table">
                                    <?php
                                    // Fetch user data from the database
                                    $user_query = "SELECT id, name, email, password, activity_status FROM user";
                                    $user_result = mysqli_query($con, $user_query);
                                    if ($user_result) {
                                        while ($user = mysqli_fetch_assoc($user_result)) {
                                            echo "<tr>";
                                            echo "<td>{$user['id']}</td>";
                                            echo "<td>{$user['name']}</td>";
                                            echo "<td>{$user['email']}</td>";
                                            echo "<td>{$user['password']}</td>";
                                            echo "<td id='activity_status_{$user['id']}'>{$user['activity_status']}</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>Error fetching user data.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Heartbeat function
            function heartbeat() {
                // Makes an AJAX request to get the latest user activity status
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_latest_user_status.php', true);

                // Send the request
                xhr.send();

                // This will be called after the response is received
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        // Update the user activity status in the table
                        // You may need to reload the entire table or just update the specific cells
                        // depending on your requirements
                        var userStatusData = JSON.parse(xhr.responseText);
                        for (var userId in userStatusData) {
                            var activityStatusCell = document.getElementById('activity_status_' + userId);
                            activityStatusCell.innerHTML = userStatusData[userId];

                            // Change background color based on activity status
                            if (userStatusData[userId] === 'Active') {
                                activityStatusCell.style.backgroundColor = 'green';
                            } else {
                                activityStatusCell.style.backgroundColor = 'red';
                            }
                        }
                    }
                };

                // Schedule the next heartbeat after a certain interval
                setTimeout(heartbeat, 5000); // 10 seconds
            }

            // Start the heartbeat when the page is loaded
            document.addEventListener('DOMContentLoaded', function() {
                heartbeat();
            });
        </script>
</body>

</html>