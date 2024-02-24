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

// Retrieve data from the database
$sql = "SELECT ql.qlesson_id, ql.title, SUM(qa.end_time) AS total_time FROM quiz_attempts qa INNER JOIN quizlessons ql ON qa.qlesson_id = ql.qlesson_id GROUP BY qa.qlesson_id";
$result = mysqli_query($con, $sql);

if (!$result) {
    die('Error fetching quiz attempts: ' . mysqli_error($con));
}

$quizData = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Convert time from seconds to minutes
    $totalTimeMinutes = round($row['total_time'] / 60, 2);
    $quizData[$row['title']] = $totalTimeMinutes;
}
?>

<body>
    <!-- QUIZ LESSONS TIME PIE CHART -->
    <div class="chart-container">
        <canvas id="pieChart"></canvas>
    </div>

    <style>
        .chart-container {
            width: 50%;
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data for the pie chart
        const quizData = <?php echo json_encode($quizData); ?>;

        // Get the canvas element
        const ctx = document.getElementById('pieChart').getContext('2d');

        // Create the pie chart
        const pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(quizData),
                datasets: [{
                    label: 'Total Time Taken (Minutes)',
                    data: Object.values(quizData),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Quiz Lessons Total Time Taken (Minutes)'
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            // Retrieve the quiz lesson title based on the index of the hovered element
                            const quizLessonTitle = data.labels[tooltipItem.index];
                            // Retrieve the time taken for the quiz lesson
                            const timeTaken = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                            // Format the tooltip label to display the quiz lesson title and time taken
                            return `${quizLessonTitle}: ${timeTaken} minutes`;
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>