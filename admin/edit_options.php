<?php
session_start();
include("include/header.php");
include("../config/db.php");

// Check if question_id is provided in the URL
if (isset($_GET['question_id'])) {
    $questionId = $_GET['question_id'];

    // Fetch question details
    $sqlQuestion = "SELECT * FROM questions WHERE question_id = $questionId";
    $resultQuestion = mysqli_query($con, $sqlQuestion);

    if (!$resultQuestion) {
        die('Error: ' . mysqli_error($con));
    }

    $question = mysqli_fetch_assoc($resultQuestion);

    // Check if the question is of multiple-choice type
    $sqlOptionsMulti = "SELECT * FROM options_multiple_choice WHERE question_id = $questionId";
    $resultOptionsMulti = mysqli_query($con, $sqlOptionsMulti);

    // Check if the question is of order type
    $sqlOptionsOrder = "SELECT * FROM options_order_type WHERE question_id = $questionId";
    $resultOptionsOrder = mysqli_query($con, $sqlOptionsOrder);

    // Handle form submission to update options
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update options for multiple-choice questions
        while ($rowMulti = mysqli_fetch_assoc($resultOptionsMulti)) {
            $optionId = $rowMulti['option_id'];
            $newOptionText = $_POST['option_' . $optionId];
            $isCorrect = isset($_POST['is_correct_' . $optionId]) ? 1 : 0;

            $sqlUpdateMulti = "UPDATE options_multiple_choice SET option_text = '$newOptionText', is_correct = $isCorrect WHERE option_id = $optionId";
            mysqli_query($con, $sqlUpdateMulti);
        }

        // Update options for order-type questions
        while ($rowOrder = mysqli_fetch_assoc($resultOptionsOrder)) {
            $optionId = $rowOrder['option_id'];
            for ($i = 1; $i <= 6; $i++) {
                $newOptionText = $_POST['option_' . $optionId . '_' . $i];
                $sqlUpdateOrder = "UPDATE options_order_type SET option_$i = '$newOptionText' WHERE option_id = $optionId";
                mysqli_query($con, $sqlUpdateOrder);
            }
        }

        header("location: quiz.php");
        exit();
    }
} else {
    // Redirect to the quiz question page without updating if question_id is not provided
    $quizQuestionPageUrl = "quiz_question_page.php?question_id=" . $questionId;
    header("location: $quizQuestionPageUrl");
    exit();
}
include("include/sidebar.php");
?>

<body>
    <div class="container mt-5">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h1>Edit Options for Question: <?php echo $question['question_text']; ?></h1>
            </div>
            <div class="card-body">

                <form method="POST">
                    <?php if ($resultOptionsMulti && mysqli_num_rows($resultOptionsMulti) > 0) : ?>
                        <h2>Multiple Choice Options</h2>
                        <?php while ($rowMulti = mysqli_fetch_assoc($resultOptionsMulti)) : ?>
                            <div class="mb-3">
                                <label for="option_<?php echo $rowMulti['option_id']; ?>" class="form-label">Option Text:</label>
                                <input type="text" name="option_<?php echo $rowMulti['option_id']; ?>" id="option_<?php echo $rowMulti['option_id']; ?>" class="form-control" value="<?php echo $rowMulti['option_text']; ?>" required>
                                <label for="is_correct_<?php echo $rowMulti['option_id']; ?>" class="form-check-label">Is Correct?</label>
                                <input type="checkbox" name="is_correct_<?php echo $rowMulti['option_id']; ?>" id="is_correct_<?php echo $rowMulti['option_id']; ?>" <?php echo $rowMulti['is_correct'] ? 'checked' : ''; ?>>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>

                    <?php if ($resultOptionsOrder && mysqli_num_rows($resultOptionsOrder) > 0) : ?>
                        <h2>Order Type Options</h2>
                        <?php while ($rowOrder = mysqli_fetch_assoc($resultOptionsOrder)) : ?>
                            <div class="mb-3">
                                <h3>Order Type Options</h3>
                                <?php for ($i = 1; $i <= 6; $i++) : ?>
                                    <label for="option_<?php echo $rowOrder['option_id'] . '_' . $i; ?>" class="form-label">Option <?php echo $i; ?>:</label>
                                    <input type="text" name="option_<?php echo $rowOrder['option_id'] . '_' . $i; ?>" id="option_<?php echo $rowOrder['option_id'] . '_' . $i; ?>" class="form-control" value="<?php echo $rowOrder['option_' . $i]; ?>" required>
                                <?php endfor; ?>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary">Update Options</button>
                </form>

            </div>
        </div>
    </div>
</body>

</html>