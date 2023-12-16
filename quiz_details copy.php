<?php
session_start();
include("include/header.php");
include("config/db.php");

// Ensure qlesson_id is set and valid
if (isset($_GET['qlesson_id']) && is_numeric($_GET['qlesson_id'])) {
    $qlesson_id = $_GET['qlesson_id'];

    // Fetch quiz lesson details
    $sql_qlesson = "SELECT * FROM quizlessons WHERE qlesson_id = $qlesson_id";
    $result_qlesson = mysqli_query($con, $sql_qlesson);

    if (!$result_qlesson) {
        die('Error: ' . mysqli_error($con));
    }

    $quizLesson = mysqli_fetch_assoc($result_qlesson);

    // Fetch questions for the quiz lesson
    $sql_questions = "SELECT * FROM questions WHERE qlesson_id = $qlesson_id";
    $result_questions = mysqli_query($con, $sql_questions);

    if (!$result_questions) {
        die('Error: ' . mysqli_error($con));
    }

    $questionsOptions = [];

    while ($question = mysqli_fetch_assoc($result_questions)) {
        // Fetch multiple-choice options
        $sql_mc_options = "SELECT * FROM options_multiple_choice WHERE question_id = {$question['question_id']}";
        $result_mc_options = mysqli_query($con, $sql_mc_options);

        if (!$result_mc_options) {
            die('Error: ' . mysqli_error($con));
        }

        $mc_options = [];

        while ($mc_option = mysqli_fetch_assoc($result_mc_options)) {
            $mc_options[] = $mc_option;
        }

        // Fetch order-type options
        $sql_ot_options = "SELECT * FROM options_order_type WHERE question_id = {$question['question_id']}";
        $result_ot_options = mysqli_query($con, $sql_ot_options);

        if (!$result_ot_options) {
            die('Error: ' . mysqli_error($con));
        }

        $ot_options = [];

        while ($ot_option = mysqli_fetch_assoc($result_ot_options)) {
            $ot_options[] = $ot_option;
        }

        // Organize data into $questionsOptions array
        $questionsOptions[] = [
            'question' => $question,
            'multiple_choice_options' => $mc_options,
            'order_type_options' => $ot_options,
        ];
    }
} else {
    // Handle invalid qlesson_id
    echo "Invalid quiz lesson ID.";
    exit;
}

// Display quiz details and questions with options
?>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="text-center mb-4"><?php echo $quizLesson['title']; ?></h2>

                <?php foreach ($questionsOptions as $index => $qo) : ?>
                    <div class="question-container mb-4">
                        <p class="question-text"><?php echo $qo['question']['question_text']; ?></p>

                        <!-- Display Multiple Choice Options -->
                        <?php if (!empty($qo['multiple_choice_options'])) : ?>
                            <div class="btn-group">
                                <?php foreach ($qo['multiple_choice_options'] as $mc_option) : ?>
                                    <button type="button" class="btn btn-secondary" onclick="selectOption(this)">
                                        <?php echo $mc_option['option_text']; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Display Order Type Options -->
                        <?php if (!empty($qo['order_type_options'])) : ?>
                            <div class="order-type-drop-area" ondragover="dragOver(event)" ondrop="drop(event, <?php echo $index; ?>)">
                                <p>Drop order type options here:</p>
                                <ul class="dropped-order-type-options" id="dropped-options-<?php echo $index; ?>">
                                    <!-- The dropped Options -->
                                </ul>
                            </div>
                            <ul class="order-type-options" id="order-type-list">
                                <?php foreach ($qo['order_type_options'] as $ot_option) : ?>
                                    <li draggable="true" ondragstart="dragStart(event)" ondragover="dragOver(event)" ondrop="drop(event, <?php echo $index; ?>)">
                                        <?php echo $ot_option['option_1']; ?>
                                    </li>
                                    <li draggable="true" ondragstart="dragStart(event)" ondragover="dragOver(event)" ondrop="drop(event, <?php echo $index; ?>)">
                                        <?php echo $ot_option['option_2']; ?>
                                    </li>
                                    <li draggable="true" ondragstart="dragStart(event)" ondragover="dragOver(event)" ondrop="drop(event, <?php echo $index; ?>)">
                                        <?php echo $ot_option['option_3']; ?>
                                    </li>
                                    <li draggable="true" ondragstart="dragStart(event)" ondragover="dragOver(event)" ondrop="drop(event, <?php echo $index; ?>)">
                                        <?php echo $ot_option['option_4']; ?>
                                    </li>
                                    <li draggable="true" ondragstart="dragStart(event)" ondragover="dragOver(event)" ondrop="drop(event, <?php echo $index; ?>)">
                                        <?php echo $ot_option['option_5']; ?>
                                    </li>
                                    <li draggable="true" ondragstart="dragStart(event)" ondragover="dragOver(event)" ondrop="drop(event, <?php echo $index; ?>)">
                                        <?php echo $ot_option['option_6']; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
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

        .order-type-options,
        .dropped-order-type-options {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .order-type-options li,
        .dropped-order-type-options li {
            cursor: move;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .order-type-options li.dragging,
        .dropped-order-type-options li.dragging {
            opacity: 0.5;
        }

        .order-type-drop-area {
            border: 2px dashed #3498db;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
    <script>
        function hoverLesson(element) {
            element.style.backgroundColor = "#2c3e50";
        }

        function unhoverLesson(element) {
            element.style.backgroundColor = "#3498db";
        }

        // Function to handle multiple-choice option selection
        function selectOption(optionElement) {
            // Deselect all options in the group
            var options = document.querySelectorAll(".btn-secondary");
            options.forEach(function(option) {
                option.classList.remove("active");
            });

            // Select the clicked option
            optionElement.classList.add("active");
        }

        function dragStart(event) {
            event.dataTransfer.setData("text/plain", event.target.innerHTML);
            event.target.classList.add("dragging");
        }

        function dragOver(event) {
            event.preventDefault();
        }

        function drop(event, questionIndex) {
            event.preventDefault();
            const data = event.dataTransfer.getData("text/plain");
            const draggedElement = document.querySelector(".dragging");

            // Create a new list item for the dropped content
            const droppedItem = document.createElement("li");
            droppedItem.innerHTML = data;

            // Append the actual dragged content to the drop area
            const dropArea = document.querySelector("#dropped-options-" + questionIndex);
            dropArea.appendChild(droppedItem);

            // Remove the dropped option from the original list
            draggedElement.parentNode.removeChild(draggedElement);

            // Reset the dragged element's styles
            draggedElement.classList.remove("dragging");
        }
    </script>
</body>

</html>