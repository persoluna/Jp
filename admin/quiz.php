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

// Check if a delete action is requested
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    // Delete options for the quiz lesson
    $deleteOptionsSql = "DELETE FROM options_multiple_choice WHERE question_id IN (SELECT question_id FROM questions WHERE qlesson_id = $deleteId)";
    mysqli_query($con, $deleteOptionsSql);

    $deleteOptionsOrderTypeSql = "DELETE FROM options_order_type WHERE question_id IN (SELECT question_id FROM questions WHERE qlesson_id = $deleteId)";
    mysqli_query($con, $deleteOptionsOrderTypeSql);

    // Delete questions for the quiz lesson
    $deleteQuestionsSql = "DELETE FROM questions WHERE qlesson_id = $deleteId";
    mysqli_query($con, $deleteQuestionsSql);

    // Delete the quiz lesson
    $deleteQuizLessonSql = "DELETE FROM quizlessons WHERE qlesson_id = $deleteId";
    mysqli_query($con, $deleteQuizLessonSql);
}

// Fetch quiz lessons data
$sql = "SELECT q.qlesson_id, q.title, COUNT(qu.question_id) AS num_questions 
        FROM quizlessons q 
        LEFT JOIN questions qu ON q.qlesson_id = qu.qlesson_id  
        GROUP BY q.qlesson_id, q.title";

$result = mysqli_query($con, $sql);

if (!$result) {
    die('Error: ' . mysqli_error($con));
}

?>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Quiz Lessons</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Number of Questions</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['num_questions']; ?></td>
                        <td>
                            <a href="edit_quiz.php?id=<?php echo $row['qlesson_id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="?delete_id=<?php echo $row['qlesson_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this quiz lesson?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>