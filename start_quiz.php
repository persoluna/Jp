<?php
session_start();
include("config/db.php");

// Retrieve user ID and quiz lesson ID
$user_id = $_SESSION['user_id'];
$qlesson_id = $_POST['qlesson_id'];

// Get current timestamp for start time
$start_time = date('Y-m-d H:i:s');

// Insert attempt details into database
$sql = "INSERT INTO quiz_attempts (user_id, qlesson_id, start_time) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($con, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iis", $user_id, $qlesson_id, $start_time);
    $success = mysqli_stmt_execute($stmt);
    $attemptId = mysqli_insert_id($con); // Retrieve auto-generated attempt ID
    mysqli_stmt_close($stmt);

    if ($success) {
        $_SESSION['attempt_id'] = $attemptId; // Store attempt ID in session
        echo json_encode(['success' => true, 'message' => 'Quiz attempt started successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error starting quiz attempt']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
mysqli_close($con);
