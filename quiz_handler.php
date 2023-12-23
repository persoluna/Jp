<?php
session_start();
include("config/db.php");

// Ensure it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure necessary data is set
    if (isset($_POST['question_id'], $_POST['selected_option_id'])) {
        $question_id = $_POST['question_id'];
        $selected_option_id = $_POST['selected_option_id'];

        // Use prepared statements to prevent SQL injection
        $sql_correct_option = "SELECT option_id, is_correct FROM options_multiple_choice WHERE question_id = ? AND is_correct = 1";
        $stmt = mysqli_prepare($con, $sql_correct_option);

        if ($stmt) {
            // Bind parameters and execute the query
            mysqli_stmt_bind_param($stmt, "i", $question_id);
            mysqli_stmt_execute($stmt);

            // Get result
            $result_correct_option = mysqli_stmt_get_result($stmt);

            if ($result_correct_option) {
                $correct_option = mysqli_fetch_assoc($result_correct_option);

                // Compare user-selected option with the correct option
                if ($selected_option_id == $correct_option['option_id']) {
                    echo json_encode(['correct' => true]);
                } else {
                    echo json_encode(['correct' => false, 'correct_option_id' => $correct_option['option_id']]);
                }
            } else {
                echo json_encode(['error' => 'Error retrieving data from the database']);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo json_encode(['error' => 'Error preparing the statement']);
        }
    } else {
        echo json_encode(['error' => 'Missing data']);
    }
} else {
    // Handle invalid request method
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
}
?>
