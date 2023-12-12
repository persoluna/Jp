<?php
session_start();
include("include/header.php");
include("../config/db.php");
include("include/sidebar.php");

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_redirect_message'] = 'You need to log in to access the dashboard';
    header("location: login.php");
    exit();
}

// Check if quiz lesson ID is provided in the URL
if (isset($_GET['id'])) {
    $quizLessonId = $_GET['id'];

    // Fetch questions for the quiz lesson
    $sqlQuestions = "SELECT * FROM questions WHERE qlesson_id = $quizLessonId";
    $resultQuestions = mysqli_query($con, $sqlQuestions);

    if (!$resultQuestions) {
        die('Error: ' . mysqli_error($con));
    }

    $questions = [];
    while ($row = mysqli_fetch_assoc($resultQuestions)) {
        $questions[] = $row;
    }
} else {
    // Redirect to the quiz lessons page if quiz lesson ID is not provided
    header("location: quiz_lessons.php");
    exit();
}

//submission for updating quiz details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle updating individual questions
    foreach ($_POST['question_id'] as $index => $questionId) {
        $newQuestionText = $_POST['new_question_text'][$index];

        // Update question text
        $updateQuestionSql = "UPDATE questions SET question_text = '$newQuestionText' WHERE question_id = $questionId";
        $updateQuestionResult = mysqli_query($con, $updateQuestionSql);

        if (!$updateQuestionResult) {
            die('Error updating question: ' . mysqli_error($con));
        }
    }

    header("location: quiz.php");
    exit();
}

?>

<body>
    <div class="container mt-5">
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="mb-3">Questions</h2>
                <form method="POST">
                    <?php foreach ($questions as $question) : ?>
                        <div class="mb-3">
                            <label for="new_question_text_<?php echo $question['question_id']; ?>" class="form-label">Question Text:</label>
                            <input type="text" name="new_question_text[]" id="new_question_text_<?php echo $question['question_id']; ?>" class="form-control" value="<?php echo $question['question_text']; ?>" required>
                            <input type="hidden" name="question_id[]" value="<?php echo $question['question_id']; ?>">
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="location.href='edit_options.php?question_id=<?php echo $question['question_id']; ?>'">
                            Edit Options
                        </button>
                        <br>
                        <br>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary">Update Questions</button>
                </form>

            </div>
        </div>
    </div>
</body>

</html>