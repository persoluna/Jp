<?php
session_start();
include "config/db.php";

// Fetch user data based on the current user's ID (adjust this based on your session structure)
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $userQuery = "SELECT name, xp FROM user u
                    JOIN user_xp uxp ON u.id = uxp.user_id
                    WHERE u.id = $userId";

    $userResult = mysqli_query($con, $userQuery);

    if ($userRow = mysqli_fetch_assoc($userResult)) {
        $userName = $userRow['name'];
        $userXP = $userRow['xp'];
    } else {
        // Handle the case where user data is not found
        $userName = 'Unknown';
        $userXP = 0;
    }
} else {
    // Handle the case where user ID is not set in the session
    $userName = 'Unknown';
    $userXP = 0;
}

ob_start(); // Start output buffering
header('Content-type: image/jpeg');

// Create and output the image
$image = imagecreatefromjpeg('assets/deer2.jpg');
$textColor = imagecolorallocate($image, 19, 21, 22);
$title = "NihongoQuest";
$title2 = "I have earned a wooping amount of";
$title3 = "of 5,000 XP On NihongoQuest!";
imagettftext($image, 50, 0, 20, 230, $textColor, 'assets/Lato.ttf', "$title");
imagettftext($image, 40, 0, 50, 300, $textColor, 'assets/Lato.ttf', "$title2");
imagettftext($image, 40, 0, 50, 350, $textColor, 'assets/Lato.ttf', "$title3");
imagettftext($image, 40, 0, 50, 400, $textColor, 'assets/Lato.ttf', "XP: $userXP");
imagejpeg($image);
imagedestroy($image);

ob_end_flush(); // Flush the output buffer and turn off output buffering
exit();
