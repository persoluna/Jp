<?php
session_start();
include("config/db.php");

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit("Forbidden");
}

// Retrieve the user ID from the session
$userId = $_SESSION['user_id'];

// Get the user's day streak from the database
$sql = "SELECT total_days FROM user_streak WHERE user_id = $userId AND last_completed_date = CURDATE()";
$result = mysqli_query($con, $sql);

if (!$result) {
    http_response_code(500);
    exit("Internal Server Error");
}

// Check if there's a row for today
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $userDayStreak = $row['total_days'];

    // Mark the streak as shown
    $sqlUpdateShown = "UPDATE user_streak SET shown = 1 WHERE user_id = $userId AND last_completed_date = CURDATE()";
    mysqli_query($con, $sqlUpdateShown);

    // Return the user's day streak as JSON
    header('Content-Type: application/json');
    echo json_encode(['dayStreak' => $userDayStreak]);
} else {
    http_response_code(204); // No Content
    exit(); // No streak for today
}
