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
    // Update user status to 'Offline'
    $userId = $_SESSION['user_id'];
    $newStatus = 'Offline';

    $updateQuery = "UPDATE user SET status = '$newStatus' WHERE id = $userId";
    $updateResult = mysqli_query($con, $updateQuery);

    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or another desired page
    header("location: login.php");
    exit();
}
?>

<body>
    <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>

    <form method="post" action="">
        <button type="submit" name="logout" class="btn btn-primary">Logout</button>
    </form>
</body>

</html>
