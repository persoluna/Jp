<?php
include("../config/db.php");

// Fetch the latest user activity status
$userStatusData = array();
$userQuery = "SELECT id, activity_status FROM user";
$userResult = mysqli_query($con, $userQuery);

if ($userResult) {
    while ($user = mysqli_fetch_assoc($userResult)) {
        $userStatusData[$user['id']] = $user['activity_status'];
    }
}

// Send the latest user activity status as a JSON response
header('Content-Type: application/json');
echo json_encode($userStatusData);
?>
