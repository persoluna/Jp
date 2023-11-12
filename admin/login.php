<?php
session_start();
include("../config/db.php");

// Check if the session variable is set for login redirection
if (isset($_SESSION['login_redirect_message'])) {
    echo '<div class="alert alert-warning">' . $_SESSION['login_redirect_message'] . '</div>';
    // Unset the session variable to avoid displaying it again
    unset($_SESSION['login_redirect_message']);
}


if (isset($_POST['submit'])) {
    extract($_POST);

    $sql = "SELECT id, name FROM admin WHERE email='$email' AND password='$password'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        
        // Login successful
        $row = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_name'] = $row['name'];

        header("location: admin_dash.php");
    } else {
        echo "Wrong  username or password";
    }
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Japanese Vocabulary</title>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <h1>ADMIN LOGIN</h1>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" aria-describedby="emailHelp" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
    </form>
</body>

</html>