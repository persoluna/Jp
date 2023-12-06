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
    <style>
        body {
            background: url('../assets/gl.gif') fixed center;
            background-size: cover;
        }

        .card {
            background: transparent;
            border: none;
            /* Remove the border to make it completely transparent */
        }

        .card-body {
            background: none;
            /* Remove any additional background styling for the card body */
        }

        .form-control {
            background: rgba(255, 255, 255, 0.5) !important;
            /* Adjust the alpha value for transparency */
            border: 1px solid #ced4da;
            /* Add a border to the transparent input fields */
        }

        /* styles for the button */
        .custom-button-container {
            text-align: center;
            margin-top: 10px;
        }

        .card-title {
            text-shadow: 0 0 3px #FF0000, 0 0 5px #0000FF;
        }

        .custom-button {
            background-color: #4b0082;
            /* Dark purple */
            color: #ffffff;
            padding: 7px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .custom-button:hover {
            background-color: #ffffff;
            color: #4b0082;
        }
    </style>

</head>

<body>
    <br>
    <br>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">
                            <strong>ADMIN LOGIN</strong>
                        </h2>
                        <br>
                        <form method="POST">
                            <div class="mb-3">
                                <input type="email" class="form-control" aria-describedby="emailHelp" name="email" placeholder="Enter your Email">
                            </div>
                            <br>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Enter your Password">
                            </div>
                            <br>
                            <div class="custom-button-container">
                                <button type="submit" class="custom-button" name="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>