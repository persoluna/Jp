<?php
// Include the database connection
include("config/db.php");

// Check if the user ID and lesson ID are provided via POST
if (isset($_POST['userId']) && isset($_POST['lessonId'])) {
    // Retrieve user ID and lesson ID from POST data
    $userId = $_POST['userId'];
    $lessonId = $_POST['lessonId'];

    // Function to get the last attempt time
    function getLastAttemptTime($userId, $lessonId, $con)
    {
        $sql = "SELECT end_time FROM quiz_attempts WHERE user_id = $userId AND qlesson_id = $lessonId ORDER BY attempt_id DESC LIMIT 1";
        $result = mysqli_query($con, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['end_time'];
        }
        return null;
    }

    // Call the function to get the last attempt time
    $lastAttemptTime = getLastAttemptTime($userId, $lessonId, $con);

    // Return the last attempt time as JSON
    echo json_encode($lastAttemptTime);
} else {
    // Return an error message if user ID or lesson ID is not provided
    echo json_encode("Error: User ID or Lesson ID not provided.");
}
