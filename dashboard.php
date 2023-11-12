<?php
session_start();
include("include/header.php"); // Includes the header content
include("config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}
?>

<body>

<h1>Warucomu.....<?php echo $_SESSION['user_name']; ?></h1>

</body>
</html>
