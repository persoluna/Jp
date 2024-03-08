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
        <hr>

        <?php if (isset($_SESSION['success_message'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success_message']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error_message']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

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
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal_<?php echo $row['qlesson_id']; ?>">Delete</button>
                        </td>
                    </tr>
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal_<?php echo $row['qlesson_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel_<?php echo $row['qlesson_id']; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel_<?php echo $row['qlesson_id']; ?>">Confirm Delete</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this quiz lesson?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <a href="?delete_id=<?php echo $row['qlesson_id']; ?>" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <style>
        h1 {
            font-family: 'Kanit', sans-serif;
            font-weight: 400;
        }

        /* Table style */
        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        .table th {
            background-color: whitesmoke;
        }

        /* Button style */
        .btn-primary,
        .btn-danger {
            border-radius: 10px;
            padding: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</body>

</html>