<?php
session_start();
include('include/header.php');
include("config/db.php");

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT chapters.chapter_id, chapters.name, chapters.description, chapters.image, chapters.slug
          FROM favorites
          JOIN chapters ON favorites.chapter_id = chapters.chapter_id
          WHERE favorites.user_id = $user_id";
$result = mysqli_query($con, $query);

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
                    <h1>Favorite Chapters</h1>
                    <hr>
                    <div class="row">
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='col-md-3 md-2'>";
                                echo "<a href='cards.php?chapters={$row['slug']}'>";
                                echo "<div class='card shadow'>";
                                echo "<div class='card-body'>";
                                echo "<h4>{$row['name']}</h4>";
                                echo "<img src='admin/uploads/{$row['image']}' alt='Chapters Image' class='w-100'>";
                                echo "<hr>";
                                echo "<p>{$row['description']}</p>";
                                echo "<form action='unmark_as_favorite.php' method='post'>";
                                echo "<input type='hidden' name='chapter_id' value='{$row['chapter_id']}'>";
                                echo "<button type='submit' class='btn btn-danger' title='unmark this chapter as favorite'>Unfavorite</button>";
                                echo "</form>";
                                echo "</div>";
                                echo "</div>";
                                echo "</a>";
                                echo "</div>";
                            }
                        } else {
                            echo "<img src='assets/error.gif' alt='Bootstrap' width='50' height='500'>";
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
        // Get PHP userId session variable
        var userId = <?php echo $_SESSION['user_id']; ?>;

        // On page load, set status to active
        updateStatusOnPageLoad(userId);

        // On page unload, set status to inactive
        updateStatusOnPageUnload(userId);
    </script>

</body>

</html>