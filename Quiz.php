<?php
session_start();
include("config/db.php");

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
  header("location: login.php");
  exit();
}
include("include/header.php");

//! Fetch all quiz lessons
$sql = "SELECT * FROM quizlessons";
$result = mysqli_query($con, $sql);

if (!$result) {
  die('Error: ' . mysqli_error($con));
}

$quizLessons = [];
while ($row = mysqli_fetch_assoc($result)) {
  $quizLessons[] = $row;
}

//! Fetch unlocked lessons for the current user
$userId = $_SESSION['user_id'];
$unlockedLessons = [];
$sql_unlocked = "SELECT lesson_id FROM lesson_unlocks WHERE user_id = $userId";
$result_unlocked = mysqli_query($con, $sql_unlocked);
if ($result_unlocked) {
  while ($row = mysqli_fetch_assoc($result_unlocked)) {
    $unlockedLessons[] = $row['lesson_id'];
  }
}

//* Define the vertical and horizontal gaps
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
            $lessonColor = "#4D555A"; // Default color
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
            // Determine the content to display (number or lock icon)
            $content = ($lessonColor === "#4D555A") ? "<i class='fas fa-lock'></i>" : ($i + 1);
            ?>
            <div class="quiz-lesson" onclick="showDialogueBox('<?php echo $quizLesson['qlesson_id']; ?>', '<?php echo $quizLesson['title']; ?>', event)" onmouseover="hoverLesson(this)" onmouseout="unhoverLesson(this)" style="top: <?php echo $yPos; ?>vh; left: <?php echo $xPos; ?>vw; z-index: <?php echo $i + 1; ?>; background-color: <?php echo $lessonColor; ?>">
              <span class="quiz-lesson-number"><?php echo $content; ?></span>
              <span class="quiz-lesson-title"><?php echo $quizLesson['title']; ?></span>
            </div>
          <?php endforeach; ?>
          <audio id="dialogue-sound" preload="auto">
            <source src="assets\Japan\pop_up001.mp3" type="audio/mpeg">
          </audio>
        </div>
      </div>
    </div>
  </div>

  <!-- Placeholder div for jQuery dialog -->
  <div id="quiz-dialogue-box" class="dialogue-box" style="display: none;">
    <div id="dialogue-box-arrow" class="dialogue-box-arrow"></div>
    <div id="dialogue-content" class="ui-dialog-content"></div>
  </div>

  <style>
    body {
      background-color: white;
    }

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

    /* Custom CSS for the dialogue box */
    .ui-dialog.ui-widget.ui-widget-content.ui-corner-all.ui-front.ui-dialog-buttons.ui-draggable.ui-resizable.dialogue-box {
      border: 2px solid #90d93f;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    /* title bar */
    .ui-dialog-titlebar {
      background: #b0de78;
      color: #fff;
      font-weight: bold;
      padding: 10px;
    }

    /* close button */
    .ui-dialog-titlebar-close {
      display: none;
    }

    /* dialogue content */
    .ui-dialog-content {
      background: #F9F9F9;
      color: #222;
      font-size: 16px;
      padding: 20px;
    }

    /* the button container */
    #button-container {
      display: flex;
      justify-content: center;
      margin-top: 10px;
    }

    /* buttons */
    #button-container button {
      background: #90d93f;
      border: none;
      border-radius: 5px;
      color: #fff;
      cursor: pointer;
      font-size: 14px;
      margin: 5px;
      padding: 10px 20px;
      transition: 0.3s;
    }

    /* buttons when hovered */
    #button-container button:hover {
      background: #b0de78;
      transform: scale(1.1);
    }

    /* buttons when disabled */
    #button-container button:disabled {
      background: #ccc;
      color: #999;
      cursor: not-allowed;
    }

    /* Custom CSS for the cancel button */
    .ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button:last-of-type {
      background: #ff6b6b;
      /* Change the background color of the cancel button */
      color: #fff;
      /* Change the text color of the cancel button */
      border: none;
      /* Remove the border of the cancel button */
      border-radius: 5px;
      /* Add some rounded corners to the cancel button */
      cursor: pointer;
      /* Change the cursor to a pointer when hovering over the cancel button */
      font-size: 14px;
      /* Change the font size of the cancel button */
      padding: 10px 20px;
      /* Add some padding to the cancel button */
      transition: 0.3s;
      /* Add some transition effect to the cancel button */
    }

    /* Custom CSS for the cancel button when hovered */
    .ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button:last-of-type:hover {
      background: #ff8f8f;
      /* Change the background color of the cancel button when hovered */
      transform: scale(1.1);
      /* Make the cancel button slightly larger when hovered */
    }

    /* Custom CSS for the cancel button when disabled */
    .ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button:last-of-type:disabled {
      background: #ccc;
      /* Change the background color of the cancel button when disabled */
      color: #999;
      /* Change the text color of the cancel button when disabled */
      cursor: not-allowed;
      /* Change the cursor to a not-allowed sign when hovering over the disabled cancel button */
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

    function showDialogueBox(lessonId, lessonTitle, event) {
      var lessonElement = event.currentTarget;
      var lessonColor = $(lessonElement).css('background-color');

      // Fetch last attempt time and time limit using AJAX
      var userId = <?php echo $_SESSION['user_id']; ?>;
      $.ajax({
        type: "POST",
        url: "get_last_attempt_time.php", // PHP file to handle the AJAX request
        data: {
          userId: userId,
          lessonId: lessonId
        },
        success: function(response) {
          var data = JSON.parse(response);
          var lastAttemptTime = data.lastAttemptTime;
          var timeLimit = data.timeLimit;

          // Create the dialog content including last attempt time and time limit
          var dialogueContent = "<p>Last Attempt Time: " + lastAttemptTime + "</p>";
          dialogueContent += "<p>Time Limit: " + timeLimit + " minutes</p><br>";
          dialogueContent += "<p>Start the quiz:</p><div id='button-container'><button id='start-quiz-btn'>Start " + lessonTitle + " Quiz</button></div>";

          // Create the dialog box dynamically and append it to the placeholder div
          var dialogueBox = $('<div></div>')
            .html(dialogueContent)
            .dialog({
              autoOpen: false,
              modal: true,
              title: lessonTitle + ' Quiz',
              closeOnEscape: false, // Prevent closing dialog on pressing ESC key
              showCloseButton: false, // Hide the close button in the title bar
              close: function() {
                $(this).dialog('destroy').remove();
              },
              buttons: [{
                text: "Cancel",
                click: function() {
                  $(this).dialog("close");
                }
              }]
            });

          // Set button state based on lesson color
          if (lessonColor === 'rgb(0, 128, 0)' || lessonColor === 'rgb(255, 165, 0)') {
            dialogueBox.find("#start-quiz-btn").prop("disabled", false);
            dialogueBox.find("#start-quiz-btn").click(function() {
              window.location.href = 'quiz_page.php?qlesson_id=' + lessonId;
            });
          } else {
            dialogueBox.find("#start-quiz-btn").prop("disabled", true);
          }

          // Open the dialog box
          dialogueBox.dialog('open');

          document.getElementById('dialogue-sound').play();
        },
        error: function(xhr, status, error) {
          console.error(error);
          // Handle error
        }
      });
    }
  </script>
</body>

</html>