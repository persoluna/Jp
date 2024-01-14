<?php
include("config/db.php");

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Basic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address";
    } else {
        // Check if the email is already registered
        $checkEmailQuery = $con->prepare("SELECT id FROM user WHERE email = ?");
        $checkEmailQuery->bind_param("s", $email);
        $checkEmailQuery->execute();
        $checkEmailResult = $checkEmailQuery->get_result();

        if ($checkEmailResult && $checkEmailResult->num_rows > 0) {
            echo "Email is already registered";
        } else {
            // Retrieve the image file name
            $image = $_FILES['image']['name'];

            // Define the upload path
            $path = "uploads";

            // Generate a unique filename using the current timestamp
            $image_ext = pathinfo($image, PATHINFO_EXTENSION);
            $filename = time() . '.' . $image_ext;

            // Insert user data into the database with the unique filename
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $con->prepare("INSERT INTO user (name, email, image, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $filename, $hashedPassword);
            $stmt->execute();

            // Handle file upload
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                die("File upload failed with error code " . $_FILES['image']['error']);
            } else {
                move_uploaded_file($_FILES['image']['tmp_name'], $path . '/' . $filename);
            }

            // Insert default XP record for the new user
            $newUserId = $stmt->insert_id;
            $insertXPSql = "INSERT INTO user_xp (user_id) VALUES ($newUserId)";
            mysqli_query($con, $insertXPSql);

            header("location: login.php");
        }
    }
}

include("include/header.php");
?>


<body>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Sign Up</h2>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" placeholder="Your name" name="name" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Email address</label>
                                <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="Your active email" name="email" required>
                            </div>
                            <div class="col-md-12">
                                <label for="">Profile Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <br>
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
    <style>
        /* scroll bar hiden */
        body::-webkit-scrollbar {
            display: none;
        }
    </style>
</body>

</html>