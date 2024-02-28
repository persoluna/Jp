<?php
session_start();
include("include/header.php");
include("config/db.php");

// Fetch all quiz lessons
$sql = "SELECT * FROM quizlessons";
$result = mysqli_query($con, $sql);

if (!$result) {
  die('Error: ' . mysqli_error($con));
}

$quizLessons = [];
while ($row = mysqli_fetch_assoc($result)) {
  $quizLessons[] = $row;
}

// Fetch unlocked lessons for the current user
$userId = $_SESSION['user_id'];
$unlockedLessons = [];
$sql_unlocked = "SELECT lesson_id FROM lesson_unlocks WHERE user_id = $userId";
$result_unlocked = mysqli_query($con, $sql_unlocked);
if ($result_unlocked) {
  while ($row = mysqli_fetch_assoc($result_unlocked)) {
    $unlockedLessons[] = $row['lesson_id'];
  }
}

// Define the vertical and horizontal gaps
$verticalGap = 40;
$horizontalGap = 30;

?>

<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="quiz-container d-flex flex-column align-items-center">
          <div class="chapters" onclick="location.href='chapters.php'" onmouseover=" hoverLesson1(this)" onmouseout="unhoverLesson1(this)" ">
          <span class=" quiz-lesson-title">Chapters</span>
          </div>
          <?php
          $prevXPos = 0;
          $prevYPos = 0;
          $direction = 1; // 1 for right, -1 for left
          ?>
          <?php foreach ($quizLessons as $i => $quizLesson) : ?>
            <?php
            // Calculate positions for a snake-like pattern
            $xPos = $prevXPos + ($direction * $horizontalGap);
            $yPos = $i * $verticalGap;
            $prevXPos = $xPos;
            $prevYPos = $yPos;

            // Alternate the direction after every two lessons
            if (($i + 1) % 2 === 0) {
              $direction *= -1;
            }

            // Determine the color based on user status
            $lessonColor = "gray"; // Default color
            if (empty($unlockedLessons)) { // New user
              if ($i === 0) {
                $lessonColor = "orange"; // First lesson for new user
              }
            } else { // Existing user
              if (in_array($quizLesson['qlesson_id'], $unlockedLessons)) {
                $lessonColor = "green"; // Unlocked lesson
              } elseif ($i === (count($unlockedLessons))) {
                $lessonColor = "orange"; // Next lesson after last unlocked
              }
            }
            ?>
            <div class="quiz-lesson" onclick="showDialogueBox('<?php echo $quizLesson['qlesson_id']; ?>', '<?php echo $quizLesson['title']; ?>')" onmouseover="hoverLesson(this)" onmouseout="unhoverLesson(this)" style="top: <?php echo $yPos; ?>vh; left: <?php echo $xPos; ?>vw; z-index: <?php echo $i + 1; ?>; background-color: <?php echo $lessonColor; ?>"> <span class="quiz-lesson-number"><?php echo $i + 1; ?></span>
              <span class="quiz-lesson-title"><?php echo $quizLesson['title']; ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <!-- Dialogue box for quiz -->
  <div id="quiz-dialogue-box" class="dialogue-box">
    <p>Start the quiz:</p>
    <button id="start-quiz-btn">Start Quiz</button>
  </div>

  <style>
    /* scroll bar hiden */
    body::-webkit-scrollbar {
      display: none;
    }

    .quiz-container {
      position: relative;
      margin-top: 5vh;
      margin-left: 20vh;
    }

    .chapters {
      border-radius: 50%;
      width: 11vw;
      height: 11vw;
      background-color: rgba(128, 128, 0, 0.7);
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: box-shadow 0.3s, transform 0.3s;
      position: absolute;
      transform: translate(300%, -20%);
    }

    .chapters:hover {
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.6);
    }

    .quiz-lesson {
      border-radius: 50%;
      width: 17vw;
      height: 17vw;
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: box-shadow 0.3s, transform 0.3s;
      position: absolute;
      transform: translateX(-50%);
    }

    .quiz-lesson:hover {
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.6);
    }

    .quiz-lesson-number {
      font-size: 3vw;
      font-weight: bold;
      color: #fff;
    }

    .quiz-lesson-title {
      font-size: 2vw;
      text-align: center;
      color: #fff;
    }

    .dialogue-box {
      position: absolute;
      background-color: #fff;
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      z-index: 999;
      display: none;
      /* Hide initially */
    }

    .dialogue-box:after {
      content: '';
      position: absolute;
      border-style: solid;
      border-width: 10px 10px 0;
      border-color: #fff transparent;
      display: block;
      width: 0;
      z-index: 1;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
    }
  </style>

  <script>
    function hoverLesson1(element) {
      element.style.backgroundColor = "rgba(34, 139, 34, 0.6)";
    }

    function unhoverLesson1(element) {
      element.style.backgroundColor = "rgba(128, 128, 0, 0.7)";
    }

    function hoverLesson(element) {
     //! baki hei karna abhi
    }

    function unhoverLesson(element) {
     //! baki hei karna abhi
    }

    function showDialogueBox(lessonId, lessonTitle) {
      var dialogueBox = document.getElementById('quiz-dialogue-box');
      var startQuizBtn = document.getElementById('start-quiz-btn');
      dialogueBox.style.display = 'block';
      // Position the dialogue box near the clicked quiz lesson
      var lessonElement = event.currentTarget;
      var rect = lessonElement.getBoundingClientRect();
      dialogueBox.style.top = rect.bottom + 'px';
      dialogueBox.style.left = rect.left + 'px';
      // Update the button text and link
      startQuizBtn.textContent = 'Start ' + lessonTitle + ' Quiz';
      startQuizBtn.onclick = function() {
        window.location.href = 'quiz_page.php?qlesson_id=' + lessonId;
      };
    }
  </script>
</body>

</html>