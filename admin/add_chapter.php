<?php
session_start();
include("include/header.php");
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
  $_SESSION['login_redirect_message'] = 'You need to log in to access the page';
  header("location: login.php");
  exit();
}

// Check for success message and display it
if (isset($_SESSION['success_message'])) {
  echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
  unset($_SESSION['success_message']); // Remove the message after displaying it
}

// Check for error message and display it
if (isset($_SESSION['error_message'])) {
  echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
  unset($_SESSION['error_message']); // Remove the message after displaying it
}
?>

<body>

  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Add Chapter</h4>
            <a href="admin_dash.php">Dashboard</a>
            <br>
            <a href="all_chapters.php">all chapters</a>
          </div>
          <div class="card-body">
            <form action="action.php" method="POST" enctype="multipart/form-data">
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