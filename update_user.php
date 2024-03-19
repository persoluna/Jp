<?php
session_start();

// Include necessary files
include("config/db.php");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        exit("Unauthorized access");
    }

    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    // Get new name and email from the POST data
    $newName = $_POST['newName'];
    $newEmail = $_POST['newEmail'];

    // Update user's name and email in the database
    $updateQuery = "UPDATE user SET name = '$newName', email = '$newEmail' WHERE id = $user_id";
    // Update session variable with new name
    $_SESSION['user_name'] = $newName;

    if (mysqli_query($con, $updateQuery)) {
        // Update successful
        http_response_code(200);
        // Update successful
        $_SESSION['notification'] = "User details updated successfully";
        exit("User details updated successfully");
    } else {
        // Update failed
        http_response_code(500);
        exit("Error updating user details: " . mysqli_error($con));
    }
} else {
    // If the request method is not POST, return an error
    http_response_code(405);
    exit("Method Not Allowed");
}
