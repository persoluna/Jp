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
$sql = "SELECT name, email, image FROM user WHERE id = $user_id";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $user_name = $user_data['name'];
    $user_email = $user_data['email'];
    $profile_pic = $user_data['image'];
} else {
    header("location: login.php");
    exit();
}

// Fetch user XP data from the user_xp table
$query = "SELECT u.xp, s.total_days
          FROM user_xp AS u
          JOIN user_streak AS s ON u.user_id = s.user_id
          WHERE u.user_id = $user_id";
$result = mysqli_query($con, $query);

// Check if query was successful
if ($result && mysqli_num_rows($result) > 0) {
    $xp_data = mysqli_fetch_assoc($result);
    $total_xp = $xp_data['xp'];
    $day_streak = $xp_data['total_days'];
} else {
    // Handle error or set default values
    $total_xp = 0;
    $day_streak = 0;
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
                        <button class="btn btn-outline-secondary position-absolute top-0 end-0 m-3" type="button" id="editName"><i class="fas fa-pen"></i></button>
                        <br>
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <img src="uploads/<?php echo $profile_pic; ?>" alt="Profile Picture" class="profile-pic">
                                <div class="total-xp">Total Points: <?php echo $total_xp; ?></div>
                                <div class="day-streak">Day Streak: <?php echo $day_streak; ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="userName">Profile Name:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="userName" value="<?php echo $user_name; ?>" disabled>
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="userEmail">Email:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="userEmail" value="<?php echo $user_email; ?>" disabled>
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary mt-2 position-absolute bottom-0 end-0 m-3" type="button" id="saveChanges">Save Changes</button>
                            </div>
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

        .card-body {
            height: 260px;
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

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid #ccc;
            object-fit: cover;
        }

        /* Custom CSS for the Total XP and Day Streak */
        .total-xp,
        .day-streak {
            font-family: Arial, sans-serif;
            font-size: 20px;
            text-align: left;
            padding: 5px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* Custom CSS for the edit button */
        #editName {
            background: #90d93f;
            /* Change the background color of the edit button */
            color: #fff;
            /* Change the text color of the edit button */
            border: none;
            /* Remove the border of the edit button */
            border-radius: 5px;
            /* Add some rounded corners to the edit button */
            padding: 5px 10px;
            /* Add some padding to the edit button */
            transition: 0.3s;
            /* Add some transition effect to the edit button */
        }

        /* Custom CSS for the edit button when hovered */
        #editName:hover {
            background: #b0de78;
            /* Change the background color of the edit button when hovered */
            transform: scale(1.1);
            /* Make the edit button slightly larger when hovered */
        }

        /* Custom CSS for the input group */
        .input-group {
            display: flex;
            /* Use flexbox layout for the input group */
            align-items: center;
            /* Align the items vertically */
            gap: 10px;
            /* Add some gap between the items */
        }

        /* Custom CSS for the input fields */
        .form-control {
            width: 80%;
            /* Set a relative width for the input fields */
            border: 2px solid #90d93f;
            /* Change the border color of the input fields */
            border-radius: 5px;
            /* Add some rounded corners to the input fields */
            padding: 5px;
            /* Add some padding to the input fields */
        }

        /* Custom CSS for the input fields when focused */
        .form-control:focus {
            outline: none;
            /* Remove the default outline of the input fields */
            border-color: #b0de78;
            /* Change the border color of the input fields when focused */
        }

        /* Custom CSS for the save button */
        #saveChanges {
            background: #90d93f;
            /* Change the background color of the save button */
            color: #fff;
            /* Change the text color of the save button */
            border: none;
            /* Remove the border of the save button */
            border-radius: 5px;
            /* Add some rounded corners to the save button */
            padding: 10px 20px;
            /* Add some padding to the save button */
            transition: 0.3s;
            /* Add some transition effect to the save button */
        }

        /* Custom CSS for the save button when hovered */
        #saveChanges:hover {
            background: #b0de78;
            /* Change the background color of the save button when hovered */
            transform: scale(1.1);
            /* Make the save button slightly larger when hovered */
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
        // Get elements
        const saveBtn = document.getElementById('saveChanges');
        const editBtns = document.querySelectorAll('#editName, #editEmail');

        // Initially hide save button
        saveBtn.style.display = 'none';

        // Add click event to edit buttons  
        editBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Enable inputs
                var userNameInput = document.getElementById('userName');
                var userEmailInput = document.getElementById('userEmail');
                userNameInput.disabled = false;
                userEmailInput.disabled = false;

                // Show save button
                saveBtn.style.display = 'block';
            });
        });

        // Hide save button on save
        saveBtn.addEventListener('click', () => {
            // Save data (AJAX request)
            var newName = document.getElementById('userName').value;
            var newEmail = document.getElementById('userEmail').value;
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Refresh the page after successful update
                        location.reload();
                    } else {
                        // Handle errors
                        console.error('Error updating user details:', xhr.responseText);
                        // Display an error message to the user
                    }
                }
            };
            xhr.open('POST', 'update_user.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('newName=' + encodeURIComponent(newName) + '&newEmail=' + encodeURIComponent(newEmail)); // Send the new name and email to the PHP script

            // Hide save button
            saveBtn.style.display = 'none';
        });
    </script>
</body>

</html>