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

//! USER ATTEMPTS ACTIVITY GRAPH
// Get the current year
$currentYear = date('Y');

// Initialize arrays to store labels and data for the chart
$labels = [];
$data = [];

// Loop through each month of the current year
for ($month = 1; $month <= 12; $month++) {
    // Format the month as 'YYYY-MM' string
    $formattedMonth = sprintf('%04d-%02d', $currentYear, $month);

    // Add the month to the labels array
    $labels[] = $formattedMonth;

    // Query to get the number of quiz attempts for the current month
    $sql = "SELECT COUNT(*) AS attempts 
            FROM quiz_attempts 
            WHERE DATE_FORMAT(start_time, '%Y-%m') = '$formattedMonth'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    // Get the number of attempts or set it to 0 if no attempts were recorded
    $attempts = ($row['attempts']) ? $row['attempts'] : 0;

    // Add the number of attempts to the data array
    $data[] = $attempts;
}

//! HISTOGRAM OF QUIZ ATTEMPT DURATIONS

// Query to fetch quiz lesson names and attempt durations
$sql = "SELECT ql.title AS lesson_title, TIMESTAMPDIFF(MINUTE, start_time, end_time) AS duration 
        FROM quiz_attempts qa
        INNER JOIN quizlessons ql ON qa.qlesson_id = ql.qlesson_id";
$result = mysqli_query($con, $sql);

// Initialize an array to store quiz lesson durations
$quizLessonDurations = [];

// Fetch data and aggregate durations for each quiz lesson
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $lessonTitle = $row['lesson_title'];
        $durationInMinutes = $row['duration']; // Duration in minutes

        // Add duration to the corresponding quiz lesson
        if (!isset($quizLessonDurations[$lessonTitle])) {
            $quizLessonDurations[$lessonTitle] = [];
        }
        $quizLessonDurations[$lessonTitle][] = $durationInMinutes;
    }
}
?>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-9 col-lg-10 px-md-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Users</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Current Day Streak</th>
                                    </tr>
                                </thead>
                                <tbody id="user_table">
                                    <?php
                                    // Fetch user data and current day streak from the database
                                    $userStreakQuery = "SELECT u.id, u.name, u.email, s.total_days 
                                                        FROM user u 
                                                        LEFT JOIN user_streak s ON u.id = s.user_id AND s.last_completed_date = CURDATE()";
                                    $userStreakResult = mysqli_query($con, $userStreakQuery);

                                    if ($userStreakResult) {
                                        while ($user = mysqli_fetch_assoc($userStreakResult)) {
                                            echo "<tr>";
                                            echo "<td>{$user['id']}</td>";
                                            echo "<td>{$user['name']}</td>";
                                            echo "<td>{$user['email']}</td>";
                                            echo "<td>{$user['total_days']}</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>Error fetching user data and streak.</td></tr>";
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

    <!-- USER ATTEMPTS ACTIVITY GRAPH -->
    <div class="chart">
        <canvas id="quizAttemptsChart" width="800" height="400"></canvas>
    </div>

    <!-- HISTOGRAM OF QUIZ ATTEMPT DURATIONS -->
    <div class="chart">
        <canvas id="quizDurationHistogram" width="800" height="400"></canvas>
    </div>

    <style>
        .chart {
            width: 80%;
            height: 600px;
            margin: 20px auto;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #eee;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
    <script>
        // PHP array to JavaScript conversion
        var labels = <?php echo json_encode($labels); ?>;
        var data = <?php echo json_encode($data); ?>;

        // Create a new chart context
        var ctx = document.getElementById('quizAttemptsChart').getContext('2d');

        // Create the chart
        var quizAttemptsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Quiz Attempts',
                    data: data,
                    backgroundColor: 'rgba(255, 178, 178, 0.6)', // Bar color
                    borderColor: 'rgba(255, 178, 178, 1)', // Border color
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true // Start y-axis at 0
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Number of Quiz Attempts'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }]
                }
            }
        });
    </script>
    <script>
        // Convert PHP array to JavaScript
        var quizLessonDurations = <?php echo json_encode($quizLessonDurations); ?>;

        // Extract lesson titles and durations
        var lessonTitles = Object.keys(quizLessonDurations);
        var durations = Object.values(quizLessonDurations);

        // Process durations to create the dataset for the histogram
        var dataset = [];
        var allDurations = [];

        durations.forEach(function(durationsForLesson, index) {
            var lessonTitle = lessonTitles[index];
            allDurations = allDurations.concat(durationsForLesson);

            // Convert durations to dataset format (array of objects)
            var data = durationsForLesson.map(function(duration) {
                return {
                    x: duration,
                    y: 1 // Frequency (all durations are treated as individual data points)
                };
            });

            // Add the dataset for the lesson
            dataset.push({
                label: lessonTitle, // Use lesson title as label
                data: data,
                backgroundColor: 'rgba(255, 178, 178, 0.6)', // Bar color
                borderColor: 'rgba(255, 178, 178, 1)', // Border color
                borderWidth: 1
            });
        });

        // Create a new chart context
        var ctx = document.getElementById('quizDurationHistogram').getContext('2d');

        // Create the histogram
        var quizDurationHistogram = new Chart(ctx, {
            type: 'bar', // Change to bar chart
            data: {
                labels: allDurations, // Set all durations as labels on X-axis
                datasets: dataset
            },
            options: {
                scales: {
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Quiz Duration (minutes)'
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Frequency'
                        }
                    }]
                }
            }
        });
    </script>

</body>

</html>