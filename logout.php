<?php
session_start();
include("config/db.php");

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
    header("location: index.php");
    exit();
}
?>