<?php
session_start();
include("config/db.php");

if (isset($_GET['chapterId'])) {
    $userId = $_SESSION['user_id'];
    $chapterId = $_GET['chapterId'];

    // Check if the favorite already exists
    $checkQuery = "SELECT * FROM favorites WHERE user_id = $userId AND chapter_id = $chapterId";
    $checkResult = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        // If not, insert into favorites table
        $insertQuery = "INSERT INTO favorites (user_id, chapter_id) VALUES ({$_SESSION['user_id']}, $chapterId)";
        $insertResult = mysqli_query($con, $insertQuery);

        if ($insertResult) {
            echo "Chapter marked as favorite!";
        } else {
            echo "Error marking chapter as favorite!";
        }
    } else {
        echo "Chapter is already marked as favorite!";
    }
}
?>

