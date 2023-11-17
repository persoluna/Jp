<?php
include("include/header.php");
include("config/db.php");

if (isset($_POST['submit'])) {
    extract($_POST);

    // Basic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address";
    } else {
        // Check if the email is already registered
        $checkEmailQuery = "SELECT id FROM user WHERE email = '$email'";
        $checkEmailResult = mysqli_query($con, $checkEmailQuery);

        if ($checkEmailResult && mysqli_num_rows($checkEmailResult) > 0) {
            echo "Email is already registered";
        } else {
            // Insert user data into the database
            $sql = "INSERT INTO user (name, email, password) VALUES ('$name', '$email', '$password')";
            $result = mysqli_query($con, $sql);

            if ($result) {
                header("location: login.php");
            }
        }
    }
}
?>

<body>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Sign Up</h2>

                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" placeholder="Your name" name="name" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Email address</label>
                                <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="Your active email" name="email" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" placeholder="Strong password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>