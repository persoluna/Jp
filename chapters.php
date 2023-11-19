<?php
session_start();
include('include/header.php');
include("config/db.php");

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}
?>

<!-- to remove the blue line under the text -->
<style>
    a {
        text-decoration: none;
    }
</style>

<body>

    <div class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Chapters</h1>
                    <hr>
                    <div class="row">
                        <?php
                        function getAllActive($table)
                        {
                            global $con;
                            $query = "SELECT * FROM $table WHERE status='0' ";
                            return $query_run = mysqli_query($con, $query);
                        }

                        $chapters = getAllActive("chapters");

                        if (mysqli_num_rows($chapters) > 0) {
                            foreach ($chapters as $item) {
                        ?>
                                <div class="col-md-3 md-2">
                                    <a href="cards.php?chapters=<?= $item['slug']; ?>">
                                        <div class="card shadow">
                                            <div class="card-body">
                                                <h4><?= $item['name']; ?></h4>
                                                <img src="admin/uploads/<?= $item['image']; ?>" alt="Chapters Image" class="w-100">
                                                <hr>
                                                <p><?= $item['description']; ?></p>
                                                <!-- Add the Favorite button -->
                                                <button class="btn btn-primary" onclick="markAsFavorite(event, <?= $item['chapter_id']; ?>)" title="mark this chapter as favorite">Favorite</button>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                        <?php
                            }
                        } else {
                            echo "No Category found";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="activity_tracking.js"></script>
    <script src="fav_chp.js"></script>
    <script>
        // Start the activity tracking
        startHeartbeat(<?php echo $_SESSION['user_id']; ?>);
    </script>

</body>

</html>