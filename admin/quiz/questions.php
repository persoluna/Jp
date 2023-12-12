<?php
session_start();
include("../include/header.php");
include("../../config/db.php");

// Get quiz lesson ID and number of questions from session
$quizLessonId = $_SESSION['quiz_lesson_id'];
$numMultiQuestions = $_SESSION['num_multi_questions'];
$numOrderQuestions = $_SESSION['num_order_questions'];

// Initialize variables
$questionText = '';
$optionTexts = [];
$isCorrect = [];

// Handle form submission 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Insert questions
    for ($i = 1; $i <= $numMultiQuestions + $numOrderQuestions; $i++) {

        // Get question text
        $questionText = $_POST['question_' . $i];

        // Insert question 
        $sql = "INSERT INTO questions (qlesson_id, question_text)  
                VALUES ($quizLessonId, '$questionText')";

        if (mysqli_query($con, $sql)) {

            // Get last inserted question ID
            $questionId = mysqli_insert_id($con);

            // Insert options for multiple choice questions
            if ($i <= $numMultiQuestions) {

                for ($j = 1; $j <= 4; $j++) {

                    // Get option text and if correct
                    $optionTexts[$j] = $_POST['option_' . $i . "_" . $j];
                    $isCorrect[$j] = isset($_POST['is_correct_' . $i . "_" . $j]) ? 1 : 0;

                    // Insert option
                    $sql = "INSERT INTO options_multiple_choice (question_id, option_text, is_correct)
                            VALUES ($questionId, '{$optionTexts[$j]}', {$isCorrect[$j]})";

                    mysqli_query($con, $sql);
                }
            }
            // Insert options for order type questions
            else {

                // Get option texts 
                $optionTexts = [
                    $_POST["option_{$i}_1"], $_POST["option_{$i}_2"],
                    $_POST["option_{$i}_3"], $_POST["option_{$i}_4"],
                    $_POST["option_{$i}_5"], $_POST["option_{$i}_6"]
                ];

                // Insert options 
                $sql = "INSERT INTO options_order_type (question_id, option_1, option_2, 
                        option_3, option_4, option_5, option_6)  
                        VALUES ($questionId, '{$optionTexts[0]}', '{$optionTexts[1]}',
                                '{$optionTexts[2]}', '{$optionTexts[3]}', '{$optionTexts[4]}',
                                '{$optionTexts[5]}')";

                mysqli_query($con, $sql);
            }
        } else {
            echo "Error inserting question: " . mysqli_error($con);
        }
    }

    header("location: quiz_created.php");
    exit();
}
?>

<body>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">

                <div class="card bg-light">
                    <div class="card-header bg-info text-white">
                        <h4>Create Quiz Lesson</h4>
                    </div>
                    <div class="card-body">

                        <form method="POST">

                            <div class="row">

                                <?php for ($i = 1; $i <= $numMultiQuestions + $numOrderQuestions; $i++) : ?>

                                    <div class="col-md-6 mb-4">
                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="card-title">Question <?php echo $i; ?></h6>
                                            </div>
                                            <div class="card-body">

                                                <div class="mb-3">
                                                    <label for="question_<?php echo $i; ?>" class="form-label"><strong>Question Text</strong></label>
                                                    <input type="text" class="form-control" id="question_<?php echo $i; ?>" name="question_<?php echo $i; ?>" />
                                                </div>
                                                <hr>

                                                <?php if ($i <= $numMultiQuestions) : ?>

                                                    <?php for ($j = 1; $j <= 4; $j++) : ?>

                                                        <div class="mb-3">
                                                            <label for="option_<?php echo $i . "_" . $j; ?>" class="form-label">Option <?php echo $j; ?></label>
                                                            <input type="text" class="form-control" id="option_<?php echo $i . "_" . $j; ?>" name="option_<?php echo $i . "_" . $j; ?>" />

                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="is_correct_<?php echo $i . "_" . $j; ?>" name="is_correct_<?php echo $i . "_" . $j; ?>" />
                                                                <label class="form-check-label" for="is_correct_<?php echo $i . "_" . $j; ?>">Is Correct?</label>
                                                            </div>
                                                        </div>

                                                    <?php endfor; ?>

                                                <?php else : ?>

                                                    <?php for ($j = 1; $j <= 6; $j++) : ?>

                                                        <div class="mb-3">
                                                            <label for="option_<?php echo $i . "_" . $j; ?>" class="form-label">Option <?php echo $j; ?></label>
                                                            <input type="text" class="form-control" id="option_<?php echo $i . "_" . $j; ?>" name="option_<?php echo $i . "_" . $j; ?>" />
                                                        </div>

                                                    <?php endfor; ?>

                                                <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>

                                <?php endfor; ?>

                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>