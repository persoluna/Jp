<?php
session_start();
include("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    // Fetch attempt details for the selected user
    $fetchAttemptsQuery = "SELECT * FROM quiz_attempts WHERE user_id = $userId";
    $fetchAttemptsResult = mysqli_query($con, $fetchAttemptsQuery);

    if ($fetchAttemptsResult) {
        if (mysqli_num_rows($fetchAttemptsResult) > 0) {
            echo '<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Attempt ID</th>
                            <th>Quiz ID</th>
                            <th>End Time (In Minutes)</th>
                            <th>Attempt Datetime</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($attempt = mysqli_fetch_assoc($fetchAttemptsResult)) {
                $endTimeInMinutes = round($attempt['end_time'] / 60, 2); // Convert seconds to minutes and round to 2 decimal places
                echo "<tr>";
                echo "<td>{$attempt['attempt_id']}</td>";
                echo "<td>{$attempt['qlesson_id']}</td>";
                echo "<td>{$endTimeInMinutes}</td>";
                echo "<td>{$attempt['attempt_datetime']}</td>";
                echo "</tr>";
            }
            echo '</tbody></table>';
        } else {
            echo "<p>No attempts found for this user.</p>";
        }
    } else {
        echo "Error: " . mysqli_error($con);
    }
} else {
    echo "Invalid request";
}
