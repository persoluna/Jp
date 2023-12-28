<?php
session_start();
include("config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = intval($_POST['score']);
    
    // Retrieve the user ID from the session
    $userId = $_SESSION['user_id'];

    // Insert or update the user's XP in the database
    $sql = "INSERT INTO user_xp (user_id, xp) VALUES ($userId, $score) ON DUPLICATE KEY UPDATE xp = xp + VALUES(xp)";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die('Error: ' . mysqli_error($con));
    }

    // Send a response back to the client (optional)
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
