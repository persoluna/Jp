<?php
session_start();
include("config/db.php");

if (isset($_POST['logout'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("location: index.php");
    exit();
}
?>
