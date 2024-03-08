<?php
session_start();
include("include/header.php");
include("../config/db.php");
include("include/sidebar.php");

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_redirect_message'] = 'You need to log in to access the dashboard';
    header("location: login.php");
    exit();
}
?>

<body>
    <div class="container mt-4">
        <h1 class="my-4">Users who have cleared the quiz</h1>
        <hr><br>
        <div class="row">
            <div class="col-md-9 col-lg-10 px-md-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data representation in Table</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Quiz Lesson Title</th>
                                        <th>Number of Users Cleared</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch data from quizlessons table and count users who have cleared quiz lessons
                                    $query = "SELECT q.qlesson_id, q.title, COUNT(DISTINCT l.user_id) AS num_users_cleared 
                                              FROM quizlessons q 
                                              LEFT JOIN lesson_unlocks l ON q.qlesson_id = l.lesson_id 
                                              GROUP BY q.qlesson_id";
                                    $result = mysqli_query($con, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['title'] . "</td>";
                                            echo "<td>" . $row['num_users_cleared'] . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3'>No data found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-9 col-lg-10 px-md-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data representation in Chart</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="quizLessonChart" width="800" height="500"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // PHP code to fetch data from database and prepare for Chart.js
        <?php
        $labels = [];
        $data = [];

        // Fetch data from quizlessons table and count users who have cleared quiz lessons
        $query = "SELECT q.qlesson_id, q.title, COUNT(DISTINCT l.user_id) AS num_users_cleared 
                  FROM quizlessons q 
                  LEFT JOIN lesson_unlocks l ON q.qlesson_id = l.lesson_id 
                  GROUP BY q.qlesson_id";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $labels[] = $row['title'];
                $data[] = $row['num_users_cleared'];
            }
        }
        ?>

       // JavaScript code to create Chart.js chart
var ctx = document.getElementById('quizLessonChart').getContext('2d');
var quizLessonChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Number of Users Cleared',
            data: <?php echo json_encode($data); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: false, // Disable responsiveness
        maintainAspectRatio: false, // Disable aspect ratio
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});

    </script>

</body>

</html>