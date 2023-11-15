<?php
include("include/header.php");
include("config/db.php");

if (isset($_POST['submit'])) {
    extract($_POST);

    // Set default status to 'new' for new registrations
    $status = 'new';

    $sql = "INSERT INTO user (name, email, password, status) VALUES ('$name', '$email', '$password', '$status')";
    $result = mysqli_query($con, $sql);

    if ($result) {
        header("location: login.php");
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
                                <input type="text" class="form-control" placeholder="your name" name="name">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Email address</label>
                                <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="your active email" name="email">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" placeholder="stronge password" name="password">
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
