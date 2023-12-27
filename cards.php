<?php
session_start();
include('include/header.php');
include("config/db.php");

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

function getSlugActive($table, $slug)
{
    global $con;
    $query = "SELECT * FROM $table WHERE slug='$slug' AND status='0' LIMIT 1";
    return $query_run = mysqli_query($con, $query);
}

function getCardsByChapter($chapter_id)
{
    global $con;
    $query = "SELECT * FROM flashcards WHERE chapter_id='$chapter_id' AND status='0' ";
    return $query_run = mysqli_query($con, $query);
}

if (isset($_GET['chapters'])) {
    $chapter_slug = $_GET['chapters'];
    $chapter_data = getSlugActive("chapters", $chapter_slug);
    $chapter = mysqli_fetch_array($chapter_data);

    if ($chapter) {

        $cid = $chapter['chapter_id'];
?>

        <body>
            <link rel="stylesheet" href="cards.css">

            <div class="py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="row">
                                <?php
                                $cards = getCardsByChapter($cid);
                                if (mysqli_num_rows($cards) > 0) {
                                    foreach ($cards as $item) {
                                ?>
                                        <div class="col-md-3 md-2">
                                            <div class="card shadow flip-card">
                                                <div class="card-inner">
                                                    <div class="card-face card-front">
                                                        <h4 style="font-size: 28px;"><?= $item['question']; ?></h4>
                                                    </div>
                                                    <div class="card-face card-back">
                                                        <p style="font-size: 28px;"><?= $item['answer']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo "No Cards found";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php
    } else {
        echo  "Nothing for you";
    }
} else {
    echo  "You thought you will get away with that huh";
}
    ?>
    <script src="activity_tracking.js"></script>
    <script>
        // Get PHP userId session variable
        var userId = <?php echo $_SESSION['user_id']; ?>;

        // On page load, set status to active
        updateStatusOnPageLoad(userId);

        // On page unload, set status to inactive
        updateStatusOnPageUnload(userId);
    </script>
    <style>
        .card-back {
            background-color: #fff;
            color: #333;
            transform: rotateY(180deg);
        }
    </style>
        </body>

        </html>