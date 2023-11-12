<?php
session_start();
include("include/header.php");
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {

  $_SESSION['login_redirect_message'] = 'You need to log in to access the page';
  header("location: login.php");
  exit();
}

// Check if the "Add Chapter" form is submitted
if (isset($_POST['add_chapter'])) {
  // Retrieve data from the form
  $name = $_POST['name'];
  $description = $_POST['description'];
  $status = isset($_POST['status']) ? '1' : '0';
  $popular = isset($_POST['popular']) ? '1' : '0';

  // Retrieve the image file name
  $image = $_FILES['image']['name'];

  // Define the upload path
  $path = "uploads";

  // Generate a unique filename using the current timestamp
  $image_ext = pathinfo($image, PATHINFO_EXTENSION);
  $filename = time() . '.' . $image_ext;

  // Construct the SQL query for inserting data
  $chap_query = "INSERT INTO chapters
(name,description,status,popular,image) 
VALUES ('$name','$description','$status','$popular','$filename')";

  $chap_query_run = mysqli_query($con, $chap_query);

  if ($chap_query_run) {
    // Move the uploaded file to the specified directory
    move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $filename);

    header("add_chapter.php");
  } else {
    header("add_chapter.php");
  }
}

?>

<body>

  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Add Chapter</h4>
          </div>
          <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-6">
                  <label for="">Name</label>
                  <input type="text" name="name" placeholder="Enter chapter Name" class="form-control">
                </div>
                <div class="col-md-12">
                  <label for="">Description</label>
                  <textarea rows="3" name="description" placeholder="Enter a Interesting facts or Description" class="form-control"></textarea>
                </div>
                <div class="col-md-12">
                  <label for="">Upload Image</label>
                  <input type="file" name="image" class="form-control">
                </div>
                <div class="col-md-6">
                  <label for="">Status Hide/Show</label>
                  <input type="checkbox" name="status">
                </div>
                <div class="col-md-6">
                  <label for="">Popular</label>
                  <input type="checkbox" name="popular">
                </div>
                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary" name="add_chapter">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>