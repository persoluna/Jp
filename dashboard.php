<?php
session_start();
include("include/header.php");
include("config/db.php");

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

// Logout logic
if (isset($_POST['logout'])) {
    // Update user activity status to 'Inactive'
    $userId = $_SESSION['user_id'];
    $newActivityStatus = 'Inactive';

    $updateQuery = "UPDATE user SET activity_status = '$newActivityStatus' WHERE id = $userId";
    $updateResult = mysqli_query($con, $updateQuery);

    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("location: login.php");
    exit();
}
?>

<body>
    <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>

    <form method="post" action="">
        <button type="submit" name="logout" class="btn btn-primary">Logout</button>
    </form>

    <script>
        document.addEventListener('visibilitychange', function() {
            sendActivityUpdate();
        });

        function sendActivityUpdate() {
            var userId = <?php echo $_SESSION['user_id']; ?>;
            var newStatus = document.hidden ? 'Inactive' : 'Active';

            // Send AJAX request to update activity_status
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'update_user_activity_status.php?userId=' + userId + '&activityStatus=' + newStatus, true);
            xhr.send();
        }

        // Heartbeat function
        function heartbeat() {
            sendActivityUpdate();

            // Schedule the next heartbeat after a certain interval (e.g., 5 seconds)
            setTimeout(heartbeat, 3000);
        }

        // Start the heartbeat when the page is loaded
        document.addEventListener('DOMContentLoaded', function() {
            heartbeat();
        });
    </script>

</body>

</html>