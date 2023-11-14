<?php
session_start();
include('../include/header.php');
include("../../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_redirect_message'] = 'You need to log in to access the dashboard';
    header("location: login.php");
    exit();
}

function getByID($table, $id)
{
    global $con;
    $query = "SELECT * FROM $table WHERE id='$id'";
    return mysqli_query($con, $query);
}

if (isset($_GET['id'])) {
    $flashcard_id = $_GET['id'];
    $flashcard = getByID("flashcards", $flashcard_id);

    if (mysqli_num_rows($flashcard) > 0) {
        $flashcard_data = mysqli_fetch_array($flashcard);
    } else {
        //redirect("flashcards.php", "Flashcard not found.");
    }
} else {
    // redirect("flashcards.php", "Flashcard ID not provided.");
}

?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Flashcard</h4>
                </div>
                <div class="card-body">
                    <form action="../action.php" method="POST">
                        <input type="hidden" name="flashcard_id" value="<?= $flashcard_data['id']; ?>">
                        <div class="form-group">
                            <label for="new_question">New Question:</label>
                            <textarea class="form-control" name="new_question" rows="3"><?= $flashcard_data['question']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="new_answer">New Answer:</label>
                            <textarea class="form-control" name="new_answer" rows="3"><?= $flashcard_data['answer']; ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-secondary" name="update_flashcard_btn">Update Flashcard</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>