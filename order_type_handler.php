<?php
session_start();
include("config/db.php");

// Ensure it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Handle invalid request method
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

// Continue with the rest of your script for handling POST requests
// Retrieve user's selected options from $_POST
$userSelectedOptions = json_decode($_POST['selected_options']); // Make sure to sanitize and validate data

// Retrieve actual order-type options from the database
$questionId = $_POST['question_id']; // Assuming you're passing the question_id via POST

$sqlActualOptions = "SELECT option_1, option_2, option_3, option_4, option_5, option_6 FROM options_order_type WHERE question_id = $questionId";
$resultActualOptions = mysqli_query($con, $sqlActualOptions);

if (!$resultActualOptions) {
    die('Error: ' . mysqli_error($con));
}

$actualOptions = mysqli_fetch_assoc($resultActualOptions);

// Compare user's selection with actual options
$isCorrect = compareOptions($userSelectedOptions, $actualOptions);

// Return the result to the quiz page
echo json_encode(['correct' => $isCorrect]);

// Function to compare user's selection with actual options
function compareOptions($userSelectedOptions, $actualOptions)
{
    // Extract text values from user's options
    $userOptionTexts = array_map(function ($option) {
        return $option->text;
    }, $userSelectedOptions);

    // Extract text values from actual options
    $actualOptionTexts = array_values($actualOptions);

    // Ensure both arrays have the same count before comparing
    if (count($userOptionTexts) !== count($actualOptionTexts)) {
        return false;
    }

    // Compare the arrays to check if the order is correct
    for ($i = 0; $i < count($userOptionTexts); $i++) {
        if ($userOptionTexts[$i] !== $actualOptionTexts[$i]) {
            return false;
        }
    }

    // If all elements match, consider the order correct
    return true;
}
