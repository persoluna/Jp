<?php
include("config/db.php");
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

if (isset($_GET['userId']) && isset($_GET['activityStatus'])) {
    $userId = $_GET['userId'];
    $activityStatus = $_GET['activityStatus'];

    // Update the user activity status in the database
    $updateQuery = "UPDATE user SET activity_status = '$activityStatus' WHERE id = $userId";
    $updateResult = mysqli_query($con, $updateQuery);

    if (!$updateResult) {
        echo "Error updating user activity status: " . mysqli_error($con);
    }    
}
?>
