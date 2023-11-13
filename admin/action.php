<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
  $_SESSION['login_redirect_message'] = 'You need to log in to access the page';
  header("location: login.php");
  exit();
}

// Check if the "Add Chapter" form is submitted
if (isset($_POST['add_chapter'])) {
  // Retrieve data from the form using mysqli_real_escape_string for security
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $description = mysqli_real_escape_string($con, $_POST['description']);
  $status = isset($_POST['status']) ? '1' : '0';
  $popular = isset($_POST['popular']) ? '1' : '0';

  // Retrieve the image file name
  $image = $_FILES['image']['name'];

  // Define the upload path
  $path = "uploads";

  // Generate a unique filename using the current timestamp
  $image_ext = pathinfo($image, PATHINFO_EXTENSION);
  $filename = time() . '.' . $image_ext;

  // Construct the SQL query using prepared statements
  $chap_query = "INSERT INTO chapters (name, description, status, popular, image) VALUES (?, ?, ?, ?, ?)";

  $stmt = mysqli_prepare($con, $chap_query);
  mysqli_stmt_bind_param($stmt, 'sssss', $name, $description, $status, $popular, $filename);
  $chap_query_run = mysqli_stmt_execute($stmt);

  if ($chap_query_run) {
    // Move the uploaded file to the specified directory
    move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $filename);

    $_SESSION['success_message'] = 'Uploaded successfully';
    header("Location: add_chapter.php");
    exit();
  } else {
    $_SESSION['error_message'] = 'Something went wrong';
    header("Location: add_chapter.php");
    exit();
  }
} else if (isset($_POST['update_chapter_btn'])) {
  $chapter_id = $_POST['chapter_id'];
  $name = $_POST['name'];
  $description = $_POST['description'];
  $status = isset($_POST['status']) ? '1' : '0';
  $popular = isset($_POST['popular']) ? '1' : '0';

  $new_image = $_FILES['image']['name'];
  $old_image = $_POST['old_image'];

  if ($new_image != "") {
    $image_ext = pathinfo($new_image, PATHINFO_EXTENSION);
    $update_filename = time() . '.' . $image_ext;
  } else {
    $update_filename = $old_image;
  }
  $path = "uploads";

  $update_query = "UPDATE chapters SET name='$name', description='$description',
  status='$status', popular='$popular', image='$update_filename' WHERE chapter_id='$chapter_id' ";

  $update_query_run = mysqli_query($con, $update_query);

  if ($update_query_run) {
    if ($_FILES['image']['name'] != "") {
      move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $update_filename);
      if (file_exists("uploads/" . $old_image)) {
        unlink("uploads/" . $old_image);
      }
    }
    $_SESSION['updated_message'] = 'Updated successfully';
    header("Location: all_chapters.php");
    exit();
  } else {
    $_SESSION['error_message'] = 'Something went wrong';
    header("Location: add_chapter.php");
    exit();
  }
} else if (isset($_POST['delete_chapter_btn'])) {
  $chapter_id = mysqli_real_escape_string($con, $_POST['chapter_id']);

  $chapter_query = "SELECT * FROM chapters WHERE chapter_id='$chapter_id' ";
  $chapter_query_run = mysqli_query($con, $chapter_query);
  $chapter_data = mysqli_fetch_array($chapter_query_run);
  $image = $chapter_data['image'];

  $delete_query = "DELETE FROM chapters WHERE chapter_id=?";
  $stmt = mysqli_prepare($con, $delete_query);
  mysqli_stmt_bind_param($stmt, 'i', $chapter_id);
  $delete_query_run = mysqli_stmt_execute($stmt);
  if ($delete_query_run) {
    if (file_exists("uploads/" . $image)) {
      unlink("uploads/" . $image);
    }
    $_SESSION['deleted_message'] = 'Deleted successfully';
    header("Location: all_chapters.php");
    exit();
  } else {
    $_SESSION['error_message'] = 'Something went wrong';
    header("Location: add_chapter.php");
    exit();
  }
}
