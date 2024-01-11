<?php
session_start();

// Include necessary files
include("config/db.php");

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, image FROM user WHERE id = $user_id";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $user_name = $user_data['name'];
    $profile_pic = $user_data['image'];
} else {
    header("location: login.php");
    exit();
}

include("include/header.php");
?>

<body>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">Welcome, <?php echo $user_name; ?>!</h1>
                        <div class="text-center">
                            <img src="uploads/<?php echo $profile_pic; ?>" alt="Profile Picture" class="w-50 img-fluid rounded-circle mb-4">
                        </div>

                        <!-- Your additional content goes here -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>