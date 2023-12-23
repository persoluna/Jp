<?php
session_start();
include("include/header.php");
include("config/db.php");

if (isset($_GET['qlesson_id']) && is_numeric($_GET['qlesson_id'])) {
    $qlesson_id = $_GET['qlesson_id'];

    $sql_qlesson = "SELECT * FROM quizlessons WHERE qlesson_id = $qlesson_id";
    $result_qlesson = mysqli_query($con, $sql_qlesson);

    if (!$result_qlesson) {
        die('Error: ' . mysqli_error($con));
    }

    $quizLesson = mysqli_fetch_assoc($result_qlesson);

    $sql_questions = "SELECT * FROM questions WHERE qlesson_id = $qlesson_id";
    $result_questions = mysqli_query($con, $sql_questions);

    if (!$result_questions) {
        die('Error: ' . mysqli_error($con));
    }
} else {
    echo "Invalid quiz lesson ID.";
    exit;
}
?>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="text-center mb-4"><?php echo $quizLesson['title']; ?></h2>

                <?php
                $questionsOptions = [];

                while ($question = mysqli_fetch_assoc($result_questions)) {
                    $questionsOptions[] = $question;
                }

                foreach ($questionsOptions as $index => $qo) :
                ?>
                    <div class="question-container mb-4 <?php echo ($index === 0) ? 'active' : ''; ?>">
                        <p class="question-text"><?php echo $qo['question_text']; ?></p>

                        <!-- Display Multiple Choice Options -->
                        <?php
                        $sql_mc_options = "SELECT option_id, option_text FROM options_multiple_choice WHERE question_id = {$qo['question_id']}";
                        $result_mc_options = mysqli_query($con, $sql_mc_options);

                        if (!$result_mc_options) {
                            die('Error: ' . mysqli_error($con));
                        }


                        // Fetch all options and shuffle them
                        $options = [];
                        while ($mc_option = mysqli_fetch_assoc($result_mc_options)) {
                            $options[] = $mc_option;
                        }
                        shuffle($options);
                        ?>

                        <div class="btn-group">
                            <?php foreach ($options as $mc_option) : ?>
                                <button type="button" class="btn btn-secondary" data-question-id="<?php echo $qo['question_id']; ?>" data-option-id="<?php echo $mc_option['option_id']; ?>" onclick="selectMultipleChoiceOption(this)">
                                    <?php echo $mc_option['option_text']; ?>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <!-- Display Order Type Options -->
                        <?php
                        $sql_ot_options = "SELECT * FROM options_order_type WHERE question_id = {$qo['question_id']}";
                        $result_ot_options = mysqli_query($con, $sql_ot_options);

                        if (!$result_ot_options) {
                            die('Error: ' . mysqli_error($con));
                        }

                        // Check if there are order-type options for the current question
                        $isOrderTypeQuestion = mysqli_num_rows($result_ot_options) > 0;
                        ?>

                        <?php if ($isOrderTypeQuestion) : ?>
                            <div class="order-type-drop-area" id="dropped-options-<?php echo $index; ?>">
                                <ul class="dropped-order-type-options" onclick="unselectOrderTypeOption(event, <?php echo $index; ?>)">
                                    <!-- The dropped Options for this question -->
                                </ul>
                            </div>

                            <ul class="order-type-options">
                                <?php while ($ot_option = mysqli_fetch_assoc($result_ot_options)) : ?>
                                    <li onclick="selectOrderTypeOption(this, <?php echo $index; ?>)">
                                        <?php echo $ot_option['option_1']; ?>
                                    </li>
                                    <li onclick="selectOrderTypeOption(this, <?php echo $index; ?>)">
                                        <?php echo $ot_option['option_2']; ?>
                                    </li>
                                    <li onclick="selectOrderTypeOption(this, <?php echo $index; ?>)">
                                        <?php echo $ot_option['option_3']; ?>
                                    </li>
                                    <li onclick="selectOrderTypeOption(this, <?php echo $index; ?>)">
                                        <?php echo $ot_option['option_4']; ?>
                                    </li>
                                    <li onclick="selectOrderTypeOption(this, <?php echo $index; ?>)">
                                        <?php echo $ot_option['option_5']; ?>
                                    </li>
                                    <li onclick="selectOrderTypeOption(this, <?php echo $index; ?>)">
                                        <?php echo $ot_option['option_6']; ?>
                                    </li>
                                    <!-- Repeat for other options -->
                                <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>

                        <!-- Show the "CHECK" button -->
                        <button type="button" class="btn btn-primary" onclick="<?php echo $isOrderTypeQuestion ? 'checkOrderTypeOptions' : 'checkMultipleChoiceOption'; ?>()">CHECK</button>

                        <!-- Message indicating right or wrong answer -->
                        <p id="answer-message-<?php echo $index; ?>" class="answer-message"></p>

                        <!-- Show the "Next" button (initially hidden) -->
                        <button type="button" class="btn btn-primary" onclick="showNextQuestion()" id="next-button-<?php echo $index; ?>" style="display:none;">Next</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <style>
        .question-container {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;

        }

        .question-text {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn-secondary.active {
            background-color: #ddd;
        }

        .order-type-options {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .order-type-options li {
            cursor: pointer;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .order-type-drop-area {
            border: 2px dashed #3498db;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }

        .dropped-order-type-options {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .dropped-order-type-options li {
            cursor: move;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .btn-primary {
            /* Add your button styles */
            margin-top: 10px;
        }
    </style>
    <script>
        let currentQuestionIndex = 0;
        let selectedOptionId = null;
        let selectedOrderTypeOptions = [];
        let questionsOptions = <?php echo json_encode($questionsOptions); ?>;

        function showActiveQuestion() {
            const allQuestions = document.querySelectorAll('.question-container');
            allQuestions.forEach((question, index) => {
                if (index === currentQuestionIndex) {
                    question.style.display = 'block';
                } else {
                    question.style.display = 'none';
                }
            });
        }

        function showNextQuestion() {
            const nextButton = document.querySelector(`#next-button-${currentQuestionIndex}`);
            nextButton.style.display = 'none'; // Hide the "Next" button for the current question

            currentQuestionIndex++;
            selectedOptionId = null; // Reset selected option for the new question
            selectedOrderTypeOptions[currentQuestionIndex] = []; // Reset selected order-type options

            showActiveQuestion();
        }

        function selectMultipleChoiceOption(optionElement) {
            // Remove the active class from all options in the same group
            const btnGroup = optionElement.parentElement;
            btnGroup.querySelectorAll(".btn-secondary").forEach(option => {
                option.classList.remove("active");
            });

            // Add the active class to the selected option
            optionElement.classList.add("active");

            // Set the selected option ID
            selectedOptionId = optionElement.dataset.optionId;
        }

        function selectOrderTypeOption(optionElement, questionIndex) {
            // Get the respective question's drop area
            const dropArea = document.querySelector("#dropped-options-" + questionIndex + " ul");

            // Move the selected option to the drop area
            dropArea.appendChild(optionElement);

            // Store the selected option in the array
            selectedOrderTypeOptions[questionIndex] = Array.from(dropArea.children).map(option => option.textContent);
        }

        function unselectOrderTypeOption(event, questionIndex) {
            // Get the clicked option
            const clickedOption = event.target;

            // Get the respective question's original list
            const originalList = document.querySelector("#dropped-options-" + questionIndex + " + .order-type-options");

            // Move the clicked option back to the original list
            originalList.appendChild(clickedOption);

            // Update the stored selected options array
            selectedOrderTypeOptions[questionIndex] = Array.from(dropArea.children).map(option => option.textContent);
        }

        function checkMultipleChoiceOption() {
            if (selectedOptionId !== null) {
                fetch('quiz_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `question_id=${questionsOptions[currentQuestionIndex].question_id}&selected_option_id=${selectedOptionId}`,
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Display response on the quiz page
                        displayAnswerMessage(data.correct, data.correct_option_id);
                        showNextButton(data.correct);
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                    });
            } else {
                console.error('No option selected');
            }
        }

        function checkOrderTypeOptions() {
            const selectedOptions = selectedOrderTypeOptions[currentQuestionIndex];

            // Sanitize and trim the text values
            const sanitizedOptions = selectedOptions.map(option => ({
                text: option.trim().replace(/\\n/g, '')
            }));

            fetch('order_type_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `question_id=${questionsOptions[currentQuestionIndex].question_id}&selected_options=${encodeURIComponent(JSON.stringify(sanitizedOptions))}`,
                })
                .then(response => response.json())
                .then(data => {
                    // Display response on the quiz page
                    displayAnswerMessage(data.correct);
                    showNextButton(data.correct);
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                });
        }



        function displayAnswerMessage(isCorrect, correctOptionId) {
            const answerMessage = document.querySelector(`#answer-message-${currentQuestionIndex}`);
            if (isCorrect) {
                answerMessage.textContent = 'Correct!';
            } else {
                answerMessage.textContent = `Wrong! Answer`;
            }
        }

        function showNextButton(isCorrect) {
            const nextButton = document.querySelector(`#next-button-${currentQuestionIndex}`);
            if (isCorrect) {
                nextButton.style.display = 'block';
            } else {
                nextButton.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            showActiveQuestion();
        });
    </script>

</body>

</html>