<?php
session_start();
include("../config/db.php");

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
    header("Location: add_chapter.php");
    exit();
  }
}else if (isset($_POST['delete_chapter_btn'])) {
    $chapter_id = mysqli_real_escape_string($con, $_POST['chapter_id']);

    $chapter_query = "SELECT * FROM chapters WHERE chapter_id='$chapter_id' ";
    $chapter_query_run = mysqli_query($con, $chapter_query);
    $chapter_data = mysqli_fetch_array($chapter_query_run);
    $image = $chapter_data['image'];

    $delete_query = "DELETE FROM chapters WHERE chapter_id='$chapter_id'";
    $delete_query_run = mysqli_query($con, $delete_query);
    if ($delete_query_run) {
        if (file_exists("uploads/" . $image)) {
            unlink("uploads/" . $image);
        }
        $_SESSION['deleted_message'] = 'Deleted successfully';
        header("Location: all_chapters.php");
        exit();
    } else {
        header("Location: add_chapter.php");
        exit();
    }
}

    

?>
