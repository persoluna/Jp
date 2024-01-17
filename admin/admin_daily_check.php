<?php
include("../config/db.php");

if (isset($_SESSION['admin_id'])) {
    // Check if users have an entry in the day streak table for yesterday
    $sql_check_yesterday = "SELECT user_id FROM user_streak WHERE last_completed_date = CURDATE() - INTERVAL 1 DAY";
    $result_check_yesterday = mysqli_query($con, $sql_check_yesterday);

    if (!$result_check_yesterday) {
        die('Error in SELECT query: ' . mysqli_error($con));
    }

    // Loop through users with an entry for yesterday and update their streak to 0
    while ($row_user_yesterday = mysqli_fetch_assoc($result_check_yesterday)) {
        $user_id = $row_user_yesterday['user_id'];

        $sql_update_streak = "UPDATE user_streak SET total_days = 0 WHERE user_id = $user_id";
        $result_update_streak = mysqli_query($con, $sql_update_streak);

        if (!$result_update_streak) {
            die('Error in UPDATE query: ' . mysqli_error($con));
        }
    }
}
