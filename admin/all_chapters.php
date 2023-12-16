<?php
session_start();
include('include/header.php');
include('../config/db.php');
include("include/sidebar.php");


if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_redirect_message'] = 'You need to log in to access the page';
    header("location: login.php");
    exit();
}

// Check for success message and display it
if (isset($_SESSION['updated_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['updated_message'] . '</div>';
    unset($_SESSION['updated_message']); // Remove the message after displaying it
}
// Check for success message and display it
if (isset($_SESSION['deleted_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['deleted_message'] . '</div>';
    unset($_SESSION['deleted_message']); // Remove the message after displaying it
}

// Check for error message and display it
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']); // Remove the message after displaying it
}

?>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-9 col-lg-10 px-md-7">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Chapters</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>name</th>
                                        <th>Image</th>
                                        <th>Status Hide/show</th>
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
                                    $chapter = getAll("chapters");

                                    if (mysqli_num_rows($chapter) > 0) {
                                        foreach ($chapter as $item) {
                                    ?>
                                            <tr>
                                                <td> <?= $item['chapter_id']; ?></td>
                                                <td> <?= $item['name']; ?></td>
                                                <td>
                                                    <img src="uploads/<?= $item['image']; ?>" width="100px" hight="100px" alt="<?= $item['name']; ?>">
                                                </td>
                                                <td>
                                                    <?= $item['status'] == '0' ? "Visible" : "Hidden" ?>
                                                </td>

                                                <td>
                                                    <a href="edit_chapter.php?chapter_id=<?= $item['chapter_id']; ?>" class="btn btn-secondary">Edit</a>
                                                    <form action="action.php" method="POST">
                                                        <input type="hidden" name="chapter_id" value="<?= $item['chapter_id']; ?>">
                                                        <br>
                                                        <button type="submit" class="btn btn-secondary" name="delete_chapter_btn">Delete</button>
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