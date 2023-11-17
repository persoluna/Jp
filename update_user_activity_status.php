<?php
include("config/db.php");

if (isset($_GET['userId']) && isset($_GET['activityStatus'])) {
    $userId = $_GET['userId'];
    $activityStatus = $_GET['activityStatus'];

    // Update the user activity status in the database
    $updateQuery = "UPDATE user SET activity_status = '$activityStatus' WHERE id = $userId";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        echo "User activity status updated successfully!";
    } else {
        echo "Error updating user activity status!";
    }
}
?>
