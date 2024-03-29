<?php
session_start();
include("config/db.php");


//* Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

// Check if qlesson_id is set and is numeric
if (isset($_GET['qlesson_id']) && is_numeric($_GET['qlesson_id'])) {
    $qlesson_id = $_GET['qlesson_id'];
    $_SESSION['qlesson_id'] = $_GET['qlesson_id'];

    // Check if the current quiz lesson ID is the ID of the very first quiz lesson
    $sql_first_quiz_lesson = "SELECT MIN(qlesson_id) AS first_quiz_lesson FROM quizlessons";
    $result_first_quiz_lesson = mysqli_query($con, $sql_first_quiz_lesson);
    $row = mysqli_fetch_assoc($result_first_quiz_lesson);
    $first_quiz_lesson_id = $row['first_quiz_lesson'];

    if ($qlesson_id == $first_quiz_lesson_id) {
        // User is accessing the very first quiz lesson, allow access
        // Start the timer
        $_SESSION['quiz_start_time'] = time();

        // Fetch quiz lesson details
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
        // Fetch unlocked lessons for the current user
        $userId = $_SESSION['user_id'];
        $sql_check_unlocked = "SELECT lesson_id FROM lesson_unlocks WHERE user_id = $userId";
        $result_check_unlocked = mysqli_query($con, $sql_check_unlocked);

        // Store unlocked lesson IDs in an array
        $unlocked_lessons = [];
        if ($result_check_unlocked && mysqli_num_rows($result_check_unlocked) > 0) {
            while ($row = mysqli_fetch_assoc($result_check_unlocked)) {
                $unlocked_lessons[] = $row['lesson_id'];
            }
        }

        // Fetch the next quiz lesson after the highest unlocked lesson
        $sql_next_quiz_lesson = "SELECT qlesson_id FROM quizlessons WHERE qlesson_id > (SELECT MAX(lesson_id) FROM lesson_unlocks WHERE user_id = $userId) ORDER BY qlesson_id ASC LIMIT 1";
        $result_next_quiz_lesson = mysqli_query($con, $sql_next_quiz_lesson);

        if ($result_next_quiz_lesson && mysqli_num_rows($result_next_quiz_lesson) > 0) {
            $row = mysqli_fetch_assoc($result_next_quiz_lesson);
            $next_quiz_lesson_id = $row['qlesson_id'];

            if (in_array($qlesson_id, $unlocked_lessons) || $qlesson_id == $next_quiz_lesson_id) {
                // User has unlocked the quiz lesson or it's the next quiz lesson after the highest unlocked lesson, proceed to display the quiz content

                // Start the timer
                $_SESSION['quiz_start_time'] = time();

                // Fetch quiz lesson details
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
                // User has not unlocked the quiz lesson or it's not the next quiz lesson after the highest unlocked lesson, redirect to the main quiz page or display an error message
                $_SESSION['notification'] = "Please complete the above chapters to unlock this quiz lesson.";
                header("Location: quiz.php"); // Redirect to main quiz page
                exit; // Stop further execution
            }
        } else {
            // No next quiz lesson found, redirect to the main quiz page or display an error message
            header("Location: quiz.php"); // Redirect to main quiz page
            exit; // Stop further execution
        }
    }
} else {
    // Invalid or missing qlesson_id, display an error message
    echo "Invalid quiz lesson ID.";
    exit; // Stop further execution
}
include("include/header.php");

?>




<body>
    <div id="loading-animation" class="loading-animation">
        <img src="assets/Uond001.gif" alt="Loading...">
    </div>

    <div id="quiz-content" style="display: none;">
        <div class="container" style="margin-top: 60px;">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h2 class="text-center mb-4"><?php echo $quizLesson['title']; ?></h2>

                    <?php
                    $questionsOptions = [];

                    while ($question = mysqli_fetch_assoc($result_questions)) {
                        $questionsOptions[] = $question;
                    }

                    // Shuffle the array of questions
                    shuffle($questionsOptions);

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
                                        <?php
                                        // Create an array of options for the current question
                                        $options = array(
                                            $ot_option['option_1'],
                                            $ot_option['option_2'],
                                            $ot_option['option_3'],
                                            $ot_option['option_4'],
                                            $ot_option['option_5'],
                                            $ot_option['option_6']
                                        );
                                        // Shuffle the array of options
                                        shuffle($options);
                                        // Display the shuffled options in the list
                                        foreach ($options as $option) {
                                            echo "<li onclick=\"selectOrderTypeOption(this, $index)\">$option</li>";
                                        }
                                        ?>
                                    <?php endwhile; ?>
                                </ul>
                            <?php endif; ?>

                            <!-- Show the "CHECK" button -->
                            <button type="button" class="btn btn-primary" onclick="<?php echo $isOrderTypeQuestion ? 'checkOrderTypeOptions' : 'checkMultipleChoiceOption'; ?>()">CHECK</button>

                            <!-- Message indicating right or wrong answer -->
                            <p id="answer-message-<?php echo $index; ?>" class="answer-message"></p>

                            <!-- Show the "Next" button (initially hidden) -->
                            <button type="button" class="btn btn-success" onclick="showNextQuestion()" id="next-button-<?php echo $index; ?>" style="display:none;">Next</button>
                        </div>
                    <?php endforeach; ?>
                    <video id="result-video" style="display: none;">
                        <source src="assets\Japan\pop_up.mp3" type="audio/mpeg">
                    </video>
                </div>
            </div>
        </div>
    </div>
    <style>
        body {
            background-image: url('assets/japan/quiz_bg002.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .loading-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(1px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-gif {
            max-width: 50%;
            max-height: 50%;
        }

        #quiz-content {
            filter: blur(5px);
            margin: 0 auto;
            max-width: 800px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
        }

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
            margin-top: 10px;
        }

        .score-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            z-index: 9999;
            /* needs to be always on top */
        }

        .score-popup button {
            /* Style the "Back to Quiz Page" button */
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }

        .score-popup img {
            max-width: 100%;
            max-height: 200px;
            /* Adjust the height as needed */
            margin-bottom: 10px;
        }


        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9998;
        }

        .blur {
            filter: blur(20px);
        }
    </style>
    <script>
        let currentQuestionIndex = 0;
        let selectedOptionId = null;
        let selectedOrderTypeOptions = [];
        let questionsOptions = <?php echo json_encode($questionsOptions); ?>;
        let userSubmittedAnswer = false;
        let totalScore = 0;
        let totalXP = 0;
        let quizCompleted = false;

        setTimeout(function() {
            document.getElementById('loading-animation').style.display = 'none';
            document.getElementById('quiz-content').style.display = 'block';
            document.getElementById('quiz-content').style.filter = 'none';
        }, 3500);

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
            nextButton.style.display = 'none';

            currentQuestionIndex++;
            selectedOptionId = null;
            selectedOrderTypeOptions[currentQuestionIndex] = [];
            userSubmittedAnswer = false;

            showActiveQuestion();
        }

        function submitTotalXP(xp) {
            fetch('insert_xp.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `score=${xp}`,
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response if needed
                    console.log('XP Submitted:', data);
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                });
        }

        function selectMultipleChoiceOption(optionElement) {
            if (userSubmittedAnswer) {
                return;
            }

            const btnGroup = optionElement.parentElement;
            btnGroup.querySelectorAll(".btn-secondary").forEach(option => {
                option.classList.remove("active");
            });

            optionElement.classList.add("active");
            selectedOptionId = optionElement.dataset.optionId;
        }

        function selectOrderTypeOption(optionElement, questionIndex) {
            if (userSubmittedAnswer) {
                return;
            }

            const dropArea = document.querySelector("#dropped-options-" + questionIndex + " ul");
            dropArea.appendChild(optionElement);
            selectedOrderTypeOptions[questionIndex] = Array.from(dropArea.children).map(option => option.textContent);
        }

        function unselectOrderTypeOption(event, questionIndex) {
            if (userSubmittedAnswer) {
                return;
            }

            const clickedOption = event.target;
            const originalList = document.querySelector("#dropped-options-" + questionIndex + " + .order-type-options");
            originalList.appendChild(clickedOption);
            selectedOrderTypeOptions[questionIndex] = Array.from(originalList.children).map(option => option.textContent);
        }

        function checkMultipleChoiceOption() {
            if (selectedOptionId !== null && !userSubmittedAnswer) {
                fetch('quiz_handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `question_id=${questionsOptions[currentQuestionIndex].question_id}&selected_option_id=${selectedOptionId}`,
                    })
                    .then(response => response.json())
                    .then(data => {
                        displayAnswerMessage(data.correct, data.correct_option_id);
                        showNextButton(data.correct);
                        calculateScore(data.correct);

                        // Call the new function to check if it's the last question
                        checkIfLastQuestion();
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                    });
            } else {
                console.error('No option selected or user has already submitted an answer');
            }
        }

        function checkIfLastQuestion() {
            if (currentQuestionIndex === questionsOptions.length - 1) {
                submitTotalXP(totalXP);
                // Result Pop-up 
                showScorePopup();
            }

        }

        function showScorePopup() {
            // Calculate elapsed time
            const quizStartTime = <?php echo $_SESSION['quiz_start_time']; ?>;
            const currentTime = Math.floor(Date.now() / 1000); // Convert milliseconds to seconds
            const elapsedSeconds = currentTime - quizStartTime;

            let timeTaken;
            if (elapsedSeconds < 60) {
                timeTaken = `${elapsedSeconds} seconds`;
            } else {
                const elapsedMinutes = Math.floor(elapsedSeconds / 60);
                timeTaken = `${elapsedMinutes} minute${elapsedMinutes > 1 ? 's' : ''}`;
            }

            // Create a background overlay
            const overlay = document.createElement('div');
            overlay.classList.add('overlay');
            document.body.appendChild(overlay);

            // Get a random GIF from your folder
            const randomGIF = getRandomGIF();

            // Create a pop-up or modal element
            const popup = document.createElement('div');
            popup.classList.add('score-popup');
            popup.innerHTML = `
            <img src="${randomGIF}" alt="GIF">
            <p>Your Score: ${totalScore}</p>
            <p>Time Taken: ${timeTaken}</p>
            <button onclick="backToQuizPage()">Back to Quiz Page</button>
            `;
            // Append the pop-up to the body
            document.body.appendChild(popup);

            playResultSound();
        }

        function playResultSound() {
            const resultVideo = document.getElementById('result-video');
            resultVideo.currentTime = 0; // Reset to the beginning
            resultVideo.play();
        }

        function getRandomGIF() {
            const gifFolder = 'quizpage';
            const gifs = [
                '2.gif',
                '9.gif',
                '10.gif',
                '11.gif',
                '17.gif',
                '18.gif',
                '20.gif',
                '21.gif',
                '22.gif',
                '23.gif',
                '26.gif'
            ];

            // a random index
            const randomIndex = Math.floor(Math.random() * gifs.length);

            // Construct the path to the selected GIF
            return `${gifFolder}/${gifs[randomIndex]}`;
        }

        function backToQuizPage() {
            // Remove the overlay
            const overlay = document.querySelector('.overlay');
            if (overlay) {
                overlay.remove();
            }

            // Remove the blur effect
            document.body.classList.remove('blur');

            // Set the quizCompleted flag to true
            quizCompleted = true;

            // Navigate back to the main quiz page
            window.location.href = 'quiz.php';
        }

        function checkOrderTypeOptions() {
            const selectedOptions = selectedOrderTypeOptions[currentQuestionIndex];
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
                    displayAnswerMessage(data.correct);
                    showNextButton(data.correct);
                    calculateScore(data.correct);

                    // Call the new function to check if it's the last question
                    checkIfLastQuestion();
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                });
        }

        function calculateScore(isCorrect) {
            const questionScore = isCorrect ? 5 : -1;
            totalScore += questionScore;

            // Convert to XP
            const xpEarned = questionScore * 2;
            totalXP += xpEarned;

            console.log('Total Score:', totalScore);
            console.log('Total XP:', totalXP);
        }

        function displayAnswerMessage(isCorrect, correctOptionId) {
            const answerMessage = document.querySelector(`#answer-message-${currentQuestionIndex}`);
            if (isCorrect) {
                answerMessage.textContent = 'Correct!';
            } else {
                answerMessage.textContent = `Wrong! Answer: ${correctOptionId}`;
            }
        }

        function showNextButton() {
            const nextButton = document.querySelector(`#next-button-${currentQuestionIndex}`);
            nextButton.style.display = 'block';
            userSubmittedAnswer = true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            showActiveQuestion();
        });

        window.addEventListener('beforeunload', function(e) {
            if (!quizCompleted) {
                const confirmationMessage = 'Are you sure you want to leave? Your progress may be lost.';
                e.returnValue = confirmationMessage;
                return confirmationMessage;
            }
        });

        window.addEventListener('unload', function() {
            window.removeEventListener('beforeunload', function() {});
        });
    </script>
</body>

</html>