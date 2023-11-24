<?php
session_start();
include('../include/header.php');
include("../../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_redirect_message'] = 'You need to log in to access the dashboard';
    header("location: login.php");
    exit();
}

// Check for success message and display it
if (isset($_SESSION['added_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['added_message'] . '</div>';
    unset($_SESSION['added_message']);
}
// Check for success message and display it
if (isset($_SESSION['updated_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['updated_message'] . '</div>';
    unset($_SESSION['updated_message']);
}
// Check for success message and display it
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}
// Check for success message and display it
if (isset($_SESSION['deleted_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['deleted_message'] . '</div>';
    unset($_SESSION['deleted_message']);
}
// Check for success message and display it
if (isset($_SESSION['problem_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['problem_message'] . '</div>';
    unset($_SESSION['problem_message']);
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Flashcards</h4>
                    <a href="../admin_dash.php" class="btn btn-primary">Dashboard</a>
                    <a href="add_flashcard.php" class="btn btn-primary">add_flashcard</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Chapter ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            function getAll($table)
                            {
                                global $con;
                                $query = "SELECT * FROM $table";
                                return mysqli_query($con, $query);
                            }

                            $flashcards = getAll("flashcards");

                            if (mysqli_num_rows($flashcards) > 0) {
                                foreach ($flashcards as $flashcard) {
                            ?>
                                    <tr>
                                        <td><?= $flashcard['id']; ?></td>
                                        <td><?= $flashcard['question']; ?></td>
                                        <td><?= $flashcard['answer']; ?></td>
                                        <td><?= $flashcard['chapter_id']; ?></td>
                                        <td>
                                            <a href="edit_flashcards.php?id=<?= $flashcard['id']; ?>" class="btn btn-secondary">Edit</a>
                                            <form action="../action.php" method="POST">
                                                <br>
                                                <input type="hidden" name="flashcard_id" value="<?= $flashcard['id']; ?>">
                                                <button type="submit" class="btn btn-warning" name="delete_flashcard_btn">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "No records found";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>