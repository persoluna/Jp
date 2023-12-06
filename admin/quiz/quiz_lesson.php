<?php
session_start();
include("../include/header.php");
include("../../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_redirect_message'] = 'You need to log in to access the page';
    header("location: login.php");
    exit();
}

// Initialize variables
$quizTitle = '';
$numMultiQuestions = '';
$numOrderQuestions = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $quizTitle = $_POST['quiz_title'];
    $numMultiQuestions = $_POST['num_multi_questions'];
    $numOrderQuestions = $_POST['num_order_questions'];

    // Validate form data (you can add your validation logic here)

    // Insert quiz lesson data into the database
    $insertQuizLessonQuery = "INSERT INTO quizlessons (title) VALUES ('$quizTitle')";
    $result = mysqli_query($con, $insertQuizLessonQuery);

    if ($result) {
        // Get the quiz lesson ID
        $quizLessonId = mysqli_insert_id($con);

        // Store data in session variables
        $_SESSION['quiz_lesson_id'] = $quizLessonId;
        $_SESSION['num_multi_questions'] = $numMultiQuestions;
        $_SESSION['num_order_questions'] = $numOrderQuestions;

        // Move to the next step (Question Page)
        header("location: questions.php");
        exit();
    } else {
        // Handle the insertion error
        echo "Error inserting quiz lesson data: " . mysqli_error($con);
    }
}
?>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Create Quiz Lesson</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <!-- Quiz Title -->
                            <div class="mb-3">
                                <label for="quiz_title" class="form-label">Quiz Title</label>
                                <input type="text" class="form-control" id="quiz_title" name="quiz_title" required value="<?php echo $quizTitle; ?>">
                            </div>

                            <!-- Number of Multiple-Choice Questions -->
                            <div class="mb-3">
                                <label for="num_multi_questions" class="form-label">Number of Multiple-Choice Questions</label>
                                <input type="number" class="form-control" id="num_multi_questions" name="num_multi_questions" required value="<?php echo $numMultiQuestions; ?>">
                            </div>

                            <!-- Number of Order-Type Questions -->
                            <div class="mb-3">
                                <label for="num_order_questions" class="form-label">Number of Order-Type Questions</label>
                                <input type="number" class="form-control" id="num_order_questions" name="num_order_questions" required value="<?php echo $numOrderQuestions; ?>">
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Next</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
