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
        // Get PHP userId session variable
        var userId = <?php echo $_SESSION['user_id']; ?>;

        // On page load, set status to active
        updateStatusOnPageLoad(userId);

        // On page unload, set status to inactive
        updateStatusOnPageUnload(userId);
    </script>
</body>

</html>