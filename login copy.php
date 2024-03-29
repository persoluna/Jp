<?php
session_start();
include("config/db.php");

if (isset($_POST['submit'])) {
    extract($_POST);

    // Basic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address";
        header("Location: login.php");
        exit();
    } else {
        // Check if the user with the provided email and password exists
        $sql = "SELECT id, name, image, password FROM user WHERE email='$email'";
        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $hashedPassword = $row['password'];

            if (password_verify($password, $hashedPassword)) {
                // Login successful
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['profile_pic'] = $row['image'];

                header('Location: dashboard.php');
                exit();
            } else {
                // Wrong password
                $_SESSION['error'] = "Wrong username or password";
                header("Location: login.php");
                exit();
            }
        } else {
            // User not found
            $_SESSION['error'] = "Wrong username or password";
            header("Location: login.php");
            exit();
        }
    }
}

// If the error session variable is set, display the error message
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    echo "<div class='alert alert-danger'>$error</div>";
}

include("include/header.php");
?>

<body>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="img1">
                    <img src="assets/j.png" class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <div class="card shadow">
                        <div class="card-body">
                            <h2 class="card-title text-center mb-4">Login</h2>
                            <form method="POST">
                                <label class="form-label" for="email">Email address</label>
                                <div class="form-outline mb-4">
                                    <input type="email" id="email" class="form-control form-control-lg" name="email" placeholder="Enter a valid email address" required />
                                </div>

                                <!-- Password input -->
                                <label class="form-label" for="password">Password</label>
                                <div class="form-outline mb-3">
                                    <input type="password" id="password" class="form-control form-control-lg" name="password" placeholder="Enter your password" required />
                                </div>

                                <div class="text-center text-lg-start mt-4 pt-2">
                                    <button type="submit" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;" name="submit">Login</button>
                                    <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="register.php" class="link-danger">Register</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        /* scroll bar hiden */
        body::-webkit-scrollbar {
            display: none;
        }

        body {
            background-color: white;
        }
    </style>
</body>

</html>