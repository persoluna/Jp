<?php
session_start();
include('include/header.php');
include('include/sidebar.php');
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_redirect_message'] = 'You need to log in to access the dashboard';
    header("location: login.php");
    exit();
}

// * Check for success message and display it
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}

?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add Flashcard</h4>
                    <br>
                </div>
                <div class="card-body">
                    <form action="action.php" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-secondary" name="add_flashcard_btn">Save</button>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="question">Question</label>
                                        <input type="text" name="question" id="question" placeholder="Enter Question" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="answer">Answer</label>
                                        <input type="text" name="answer" id="answer" placeholder="Enter Answer" class="form-control">
                                    </div>
                                </div>
                                <br>

                                <select name="chapter_id" class="form-select mb-2">
                                    <option selected>Select Chapters</option>
                                    <?php
                                    function getAll($table)
                                    {
                                        global $con;
                                        $query = "SELECT * FROM $table";
                                        return mysqli_query($con, $query);
                                    }
                                    $chapter = getAll("chapters");

                                    if (mysqli_num_rows($chapter) > 0) {
                                        foreach ($chapter as $item) {
                                    ?>
                                            <option value="<?= $item['chapter_id']; ?>"><?= $item['name']; ?></option>
                                    <?php
                                        }
                                    } else {
                                        echo "No Category available";
                                    }
                                    ?>
                                </select>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>