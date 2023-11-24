<?php
session_start();
include("include/header.php");

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}
?>

<body>
    <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>

    <script src="activity_tracking.js"></script>
    <script>
        // Start the activity tracking
        startHeartbeat(<?php echo $_SESSION['user_id']; ?>);
    </script>

</body>

</html>
