<?php
session_start();
include("include/header.php");
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_redirect_message'] = 'You need to log in to access the dashboard';
    header("location: login.php");
    exit();
}
?>

<body>
<a href="add_chapter.php">Add chapter</a>
<h1>Warucomu.....<?php echo $_SESSION['admin_name']; ?></h1>

</body>
</html>