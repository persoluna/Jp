<?php
session_start();
include("config/db.php");

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

if (isset($_GET['chapterId'])) {
    $chapterId = $_GET['chapterId'];
    $userId = $_SESSION['user_id'];

    // Delete the favorite record from the favorites table
    $deleteQuery = "DELETE FROM favorites WHERE user_id = $userId AND chapter_id = $chapterId";
    $deleteResult = mysqli_query($con, $deleteQuery);

    if ($deleteResult) {
        // Redirect back to the favorite chapters page
        header("location: favorite_chapters.php");
        exit();
    } else {
        echo "Error removing chapter from favorites!";
    }
}
?>
