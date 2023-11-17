<?php
session_start();
include("include/header.php");
include("config/db.php");

if (isset($_POST['submit'])) {
    extract($_POST);

    // Basic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address";
    } else {
        // Check if the user with the provided email and password exists
        $sql = "SELECT id, name FROM user WHERE email='$email' AND password='$password'";
        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            // Login successful
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];

            header('Location: dashboard.php');
        } else {
            echo "Wrong username or password";
        }
    }
}
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
                                    <input type="password" id="password" class="form-control form-control-lg" name="password" placeholder="Enter password" required />
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
</body>

</html>
