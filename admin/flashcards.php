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
// Check for and display added message
if (isset($_SESSION['added_message'])) {
    echo "<script>toastr.success('" . $_SESSION['added_message'] . "', '', { positionClass: 'toast-bottom-left' });</script>";    unset($_SESSION['added_message']);
}

// Check for and display updated message
if (isset($_SESSION['updated_message'])) {
    echo "<script>toastr.success('" . $_SESSION['updated_message'] . "', '', { positionClass: 'toast-bottom-left' });</script>";    unset($_SESSION['updated_message']);
}

// Check for and display error message
if (isset($_SESSION['error_message'])) {
    echo "<script>toastr.error('" . $_SESSION['error_message'] . "', '', { positionClass: 'toast-bottom-right', toastClass: 'toast-red' });</script>";    unset($_SESSION['error_message']);
}

// Check for and display deleted message
if (isset($_SESSION['deleted_message'])) {
    echo "<script>toastr.success('" . $_SESSION['deleted_message'] . "', '', { positionClass: 'toast-bottom-right', toastClass: 'toast-red' });</script>";    unset($_SESSION['deleted_message']);
}

// Check for and display problem message         
if (isset($_SESSION['problem_message'])) {
    echo "<script>toastr.error('" . $_SESSION['problem_message'] . "', '', { positionClass: 'toast-bottom-right', toastClass: 'toast-red' });</script>";    unset($_SESSION['problem_message']);
} 
?>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Flashcards</h4>
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
                                                <form action="action.php" method="POST">
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
</body>

</html>