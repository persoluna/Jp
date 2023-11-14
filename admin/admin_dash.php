<?php
session_start();
include("include/header.php");
include("../config/db.php");

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_redirect_message'] = 'You need to log in to access the dashboard';
    header("location: login.php");
    exit();
}

?>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Users</h4>
                        <a href="all_chapters.php" class="btn btn-primary">Chapters</a>
                        <a href="flashcard/flashcards.php" class="btn btn-primary">flashcards</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch user data from the database
                                $user_query = "SELECT id, name, email, password FROM users";
                                $user_result = mysqli_query($con, $user_query);

                                if ($user_result) {
                                    while ($user = mysqli_fetch_assoc($user_result)) {
                                        echo "<tr>";
                                        echo "<td>{$user['id']}</td>";
                                        echo "<td>{$user['name']}</td>";
                                        echo "<td>{$user['email']}</td>";
                                        echo "<td>{$user['password']}</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>Error fetching user data.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>