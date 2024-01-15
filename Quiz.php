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
            ?>
            <div class="quiz-lesson" onclick="location.href='quiz_page.php?qlesson_id=<?php echo $quizLesson['qlesson_id']; ?>'" onmouseover="hoverLesson(this)" onmouseout="unhoverLesson(this)" style="top: <?php echo $yPos; ?>vh; left: <?php echo $xPos; ?>vw; z-index: <?php echo $i + 1; ?>">
              <span class="quiz-lesson-number"><?php echo $i + 1; ?></span>
              <span class="quiz-lesson-title"><?php echo $quizLesson['title']; ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
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
      background-color: rgba(0, 0, 0, 0.7);
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
      background-color: black;
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
  </style>

  <script>
    function hoverLesson1(element) {
      element.style.backgroundColor = "black";
    }

    function unhoverLesson1(element) {
      element.style.backgroundColor = "rgba(0, 0, 0, 0.7)";
    }

    function hoverLesson(element) {
      element.style.backgroundColor = "#2c3e50";
    }

    function unhoverLesson(element) {
      element.style.backgroundColor = "black";
    }
  </script>
</body>

</html>