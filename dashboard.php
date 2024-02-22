<?php
session_start();

// Include necessary files
include("config/db.php");

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, image FROM user WHERE id = $user_id";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $user_name = $user_data['name'];
    $profile_pic = $user_data['image'];
} else {
    header("location: login.php");
    exit();
}

// Fetch user XP data for the past 7 days
$chartData = [];
$currentDate = date('Y-m-d');
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days", strtotime($currentDate)));
    $query = "SELECT IFNULL(SUM(xp_earned), 0) AS total_xp 
              FROM user_xp_chart 
              WHERE user_id = $user_id AND date = '$date'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $chartData[$date] = $row['total_xp'];
}

include("include/header.php");
?>

<body>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">Welcome, <?php echo $user_name; ?>!</h1>
                        <div class="text-center">
                            <img src="uploads/<?php echo $profile_pic; ?>" alt="Profile Picture" class="w-50 img-fluid rounded-circle mb-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="chart">
        <canvas id="xpChart" width="400" height="200"></canvas>
    </div>

    <style>
        /* scroll bar hidden */
        body::-webkit-scrollbar {
            display: none;
        }

        .chart {
            width: 47%;
            height: 300px;
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
        var chartData = <?php echo json_encode($chartData); ?>;

        // Extracting labels (dates) and data (XP values) from PHP array
        var labels = Object.keys(chartData);
        var data = Object.values(chartData);

        // Create a new chart context
        var ctx = document.getElementById('xpChart').getContext('2d');

        // Create the chart
        var xpChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'User XP',
                    data: data,
                    backgroundColor: 'rgba(255, 178, 178, 0.2)',
                    borderColor: 'rgba(255, 178, 178, 1)',
                    borderWidth: 4,
                    pointBackgroundColor: 'rgba(255, 178, 178, 1)',
                    pointBorderColor: 'rgba(255, 255, 255, 1)',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointStyle: 'circle'
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day',
                            min: '<?php echo date('Y-m-d', strtotime("-6 days", strtotime($currentDate))); ?>',
                            max: '<?php echo $currentDate; ?>'
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'XP'
                        }
                    }]
                },
                elements: {
                    line: {
                        tension: 0.4
                    }
                }
            }
        });
    </script>
</body>

</html>
