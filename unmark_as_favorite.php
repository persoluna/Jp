<?php
session_start();
include("config/db.php");

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chapterId = isset($_POST['chapter_id']) ? $_POST['chapter_id'] : null;
    $userId = $_SESSION['user_id'];

    if ($chapterId !== null) {
        // Delete the favorite record from the favorites table
        $deleteQuery = "DELETE FROM favorites WHERE user_id = ? AND chapter_id = ?";
        $stmt = mysqli_prepare($con, $deleteQuery);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, 'ii', $userId, $chapterId);

        $deleteResult = mysqli_stmt_execute($stmt);

        if ($deleteResult) {
            // Redirect back to the favorite chapters page
            header("location: favorite_chapters.php");
            exit();
        } else {
            echo "Error removing chapter from favorites!";
        }
    } else {
        echo "Invalid chapter ID";
    }
} else {
    echo "Invalid request method";
}
?>
