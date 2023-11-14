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

  // Fetch flashcards associated with the chapter
  $flashcard_query = "SELECT * FROM flashcards WHERE chapter_id='$chapter_id'";
  $flashcard_query_run = mysqli_query($con, $flashcard_query);

  // Delete the associated flashcards
  while ($flashcard_data = mysqli_fetch_array($flashcard_query_run)) {
      $flashcard_id = $flashcard_data['id'];
      $delete_flashcard_query = "DELETE FROM flashcards WHERE id=?";
      $stmt = mysqli_prepare($con, $delete_flashcard_query);
      mysqli_stmt_bind_param($stmt, 'i', $flashcard_id);
      $delete_flashcard_query_run = mysqli_stmt_execute($stmt);
  }

  // Fetch image associated with the chapter
  $image_query = "SELECT image FROM chapters WHERE chapter_id='$chapter_id'";
  $image_query_run = mysqli_query($con, $image_query);
  $chapter_data = mysqli_fetch_array($image_query_run);
  $image = $chapter_data['image'];

  // Now delete the chapter
  $delete_query = "DELETE FROM chapters WHERE chapter_id=?";
  $stmt = mysqli_prepare($con, $delete_query);
  mysqli_stmt_bind_param($stmt, 'i', $chapter_id);
  $delete_query_run = mysqli_stmt_execute($stmt);

  if ($delete_query_run) {
      // Delete the associated image file
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
elseif (isset($_POST['add_flashcard_btn'])) {
  $chapter_id = $_POST['chapter_id'];
  $question = $_POST['question'];
  $answer = $_POST['answer'];

  $flashcard_query = "INSERT INTO flashcards (chapter_id,question, answer) VALUES ('$chapter_id','$question', '$answer')";
  $flashcard_query_run = mysqli_query($con, $flashcard_query);

  if ($flashcard_query_run) {
    $_SESSION['added_message'] = 'Added successfully';
    header("Location: flashcard/flashcards.php");
    exit();
  } else {
    $_SESSION['error_message'] = 'Something went wrong';
    header("Location: flashcard/add_flashcard.php");
    exit();
  }
}
if (isset($_POST['update_flashcard_btn'])) {
  $flashcard_id = $_POST['flashcard_id'];
  $new_question = $_POST['new_question'];
  $new_answer = $_POST['new_answer'];

  $update_flashcard_query = "UPDATE flashcards SET question='$new_question', answer='$new_answer' WHERE id='$flashcard_id'";
  $update_flashcard_query_run = mysqli_query($con, $update_flashcard_query);

  if ($update_flashcard_query_run) {
    $_SESSION['updated_message'] = 'updated successfully';
    header("Location: flashcard/flashcards.php");
    exit();
  } else {
    $_SESSION['error_message'] = 'Something went wrong';
    header("Location: flashcard/flashcards.php");
    exit();
  }
}
if (isset($_POST['delete_flashcard_btn'])) {
  $flashcard_id = mysqli_real_escape_string($con, $_POST['flashcard_id']);

  $delete_flashcard_query = "DELETE FROM flashcards WHERE id='$flashcard_id'";
  $delete_flashcard_query_run = mysqli_query($con, $delete_flashcard_query);

  if ($delete_flashcard_query_run) {
    $_SESSION['deleted_message'] = 'Deleted successfully';
    header("Location: flashcard/flashcards.php");
    exit();
  } else {
    $_SESSION['problem_message'] = 'Something went wrong while deleting the flashcard';
    header("Location: flashcard/flashcards.php");
    exit();
  }
}
