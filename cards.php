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

        // Array to store question-answer pairs
        $cardsArray = array();

        // Fetch questions and answers from the database
        $cards = getCardsByChapter($cid);
        if (mysqli_num_rows($cards) > 0) {
            while ($item = mysqli_fetch_assoc($cards)) {
                // Store question-answer pairs in the array
                $cardsArray[] = array(
                    'question' => $item['question'],
                    'answer' => $item['answer']
                );
            }
        }
?>

        <body>

            <div class="py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="row">
                                <?php
                                // Iterate through the array to display questions and answers on cards
                                foreach ($cardsArray as $item) {
                                ?>
                                    <div class="col-md-3 md-2">
                                        <div class="flip-card">
                                            <div class="flip-card-inner">
                                                <div class="flip-card-front">
                                                    <p class="title"><?= $item['question']; ?></p>
                                                </div>
                                                <div class="flip-card-back">
                                                    <p class="title"><?= $item['answer']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .flip-card {
                    margin-bottom: 20px;
                    background-color: transparent;
                    width: 190px;
                    height: 254px;
                    perspective: 1000px;
                }

                .title {
                    font-size: 1.5em;
                    font-weight: 900;
                    text-align: center;
                    margin: 0;
                }

                .flip-card-inner {
                    position: relative;
                    width: 100%;
                    height: 100%;
                    text-align: center;
                    transition: transform 1.5s;
                    transform-style: preserve-3d;
                }

                .flip-card:hover .flip-card-inner {
                    transform: rotateY(180deg);
                }

                .flip-card-front,
                .flip-card-back {
                    box-shadow: 0 8px 14px 0 rgba (0, 0, 0, 1);
                    position: absolute;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    width: 100%;
                    height: 100%;
                    -webkit-backface-visibility: hidden;
                    backface-visibility: hidden;
                    border: 2px solid black;
                    border-radius: 1rem;
                }

                .flip-card-front {
                    background-color: white;
                    color: red;
                }

                .flip-card-back {
                    background-color: azure;
                    color: black;
                    transform: rotateY(180deg);
                }
            </style>
        </body>

        </html>

<?php
    } else {
        echo "Nothing for you";
    }
} else {
    echo "You thought you will get away with that huh";
}
?>