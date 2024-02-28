<?php
session_start();
include("config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = intval($_POST['score']);
    $userId = $_SESSION['user_id'];
    $qlesson_id = $_SESSION['qlesson_id'];

    // ! Fetch Time limit and lesson id 
    $get_time_limit_query = "SELECT time_limit FROM quizlessons WHERE qlesson_id = $qlesson_id";
    $result_time_limit = mysqli_query($con, $get_time_limit_query);

    // ! Day Streak Logic
    $sql_check_streak = "SELECT * FROM user_streak WHERE user_id = $userId";
    $result_check_streak = mysqli_query($con, $sql_check_streak);

    if (!$result_check_streak) {
        die('Error: ' . mysqli_error($con));
    }

    // * If user has a streak record
    if (mysqli_num_rows($result_check_streak) > 0) {
        $row = mysqli_fetch_assoc($result_check_streak);
        $lastCompletedDate = strtotime($row['last_completed_date']);
        $today = strtotime(date('Y-m-d'));
        $yesterday = strtotime(date('Y-m-d', strtotime('-1 day')));

        // * If last completed date is not of today or yesterday
        if ($lastCompletedDate < $yesterday) {
            $sql_update_streak = "UPDATE user_streak SET last_completed_date = CURDATE(), total_days = 1 WHERE user_id = $userId";
            $result_update_streak = mysqli_query($con, $sql_update_streak);

            if (!$result_update_streak) {
                die('Error: ' . mysqli_error($con));
            }
        } elseif ($lastCompletedDate < $today) { // If last completed date is of yesterday
            $sql_update_streak = "UPDATE user_streak SET last_completed_date = CURDATE(), total_days = total_days + 1 WHERE user_id = $userId";
            $result_update_streak = mysqli_query($con, $sql_update_streak);

            if (!$result_update_streak) {
                die('Error: ' . mysqli_error($con));
            }
        }
    } else { // * If no streak record found, insert a new row
        $sql_insert_streak = "INSERT INTO user_streak (user_id, last_completed_date, total_days) VALUES ($userId, CURDATE(), 1)";
        $result_insert_streak = mysqli_query($con, $sql_insert_streak);

        if (!$result_insert_streak) {
            die('Error: ' . mysqli_error($con));
        }
    }

    // * Insert or update the user's XP in the database
    $sql = "INSERT INTO user_xp (user_id, xp) VALUES ($userId, $score) ON DUPLICATE KEY UPDATE xp = xp + VALUES(xp)";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die('Error: ' . mysqli_error($con));
    }

    // Insert or update the user's XP in the user_xp_chart table
    $sql_user_xp_chart = "INSERT INTO user_xp_chart (user_id, xp_earned, date) VALUES ($userId, $score, CURDATE())";
    $result_user_xp_chart = mysqli_query($con, $sql_user_xp_chart);

    if (!$result_user_xp_chart) {
        die('Error: ' . mysqli_error($con));
    }

    // Insert the elapsed time into the database
    $quiz_start_time = $_SESSION['quiz_start_time'];
    $current_time = time();
    $elapsed_time = $current_time - $quiz_start_time;

    // Get the current date and time
    $currentDateTime = date("Y-m-d H:i:s");

    $insert_time_query = "INSERT INTO quiz_attempts (user_id, qlesson_id, end_time, attempt_datetime) VALUES ($userId, $qlesson_id, $elapsed_time, '$currentDateTime')";
    $result_insert_time = mysqli_query($con, $insert_time_query);

  
    // Check if the user completed the quiz within the time limit
    $row_time_limit = mysqli_fetch_assoc($result_time_limit);
    $time_limit = $row_time_limit['time_limit']; // Time limit in minutes

    if ($elapsed_time <= $time_limit * 60) { // Convert minutes to seconds for comparison
        // Insert a record into the lesson_unlocks table for the current lesson
        $userId = $_SESSION['user_id'];

        $insert_unlock_query = "INSERT INTO lesson_unlocks (user_id, lesson_id) VALUES ($userId, $qlesson_id)";
        $result_insert_unlock = mysqli_query($con, $insert_unlock_query);

        if (!$result_insert_unlock) {
            die('Error inserting unlock record: ' . mysqli_error($con));
        }
    }

    // Send a response back to the client (optional)
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
