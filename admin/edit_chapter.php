<?php
session_start();
include('include/header.php');
include('../config/db.php');

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_redirect_message'] = 'You need to log in to access the page';
    header("location: login.php");
    exit();
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <?php
            function getByID($table, $id)
            {
                global $con;
                $query = "SELECT * FROM $table WHERE chapter_id='$id'";
                return mysqli_query($con, $query);
            }
            if (isset($_GET['chapter_id'])) {
                $id = intval($_GET['chapter_id']);
                $chapter = getByID("chapters", $id);

                if (mysqli_num_rows($chapter) > 0) {
                    $data = mysqli_fetch_array($chapter);
            ?>
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit chapter</h4>
                            <strong><a href="all_chapters.php" class="btn btn-primary">back</a></strong>
                        </div>
                        <div class="card-body">
                            <form action="action.php" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="hidden" name="chapter_id" value="<?= $data['chapter_id'] ?>">
                                        <label for="">Name</label>
                                        <input type="text" name="name" value="<?= $data['name'] ?>" placeholder="Enter chapter Name" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Slug</label>
                                        <input type="text" name="slug" value="<?= $data['slug'] ?>" placeholder="Enter slug" class="form-control">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="">Description</label>
                                        <textarea rows="3" name="description" placeholder="Enter description" class="form-control"><?= $data['description'] ?></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="">Upload Image</label>
                                        <input type="file" name="image" class="form-control">
                                        <br>
                                        <label for=""><strong>Current Image!</strong></label>
                                        <input type="hidden" name="old_image" value="<?= $data['image'] ?>">
                                        <br>
                                        <img src="uploads/<?= $data['image'] ?>" height="150px" width="200px" alt="">
                                    </div>
                                    <div class="col-md-6">
                                        <br>
                                        <label for="">Status Hide/Show</label>
                                        <input type="checkbox" <?= $data['status'] ? "checked" : "" ?> name="status">
                                    </div>
                                    <div class="col-md-6">
                                        <br>
                                        <label for="">Popular</label>
                                        <input type="checkbox" <?= $data['popular'] ? "checked" : "" ?> name="popular">
                                    </div>
                                    <div class="col-md-12">
                                        <br>
                                        <button type="submit" class="btn btn-secondary" name="update_chapter_btn">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
            <?php
                } else {
                    echo "chapter not found";
                }
            } else {
                echo "Id missing from url";
            }
            ?>

        </div>
    </div>
</div>