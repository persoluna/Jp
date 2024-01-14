<?php
session_start();
include('include/header.php');

if (!isset($_SESSION['user_id'])) {
  // User is not logged in, trigger the modal
  echo '<script type="text/javascript">$(document).ready(function() { $("#loginModal").modal("show"); });</script>';
}
?>

<body>
  <!-- Add this to your HTML body -->
  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Welcome!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <img src="assets/crazy.gif" alt="crazy GIF" class="img-fluid">
            </div>
            <div class="col-md-6">
              <br>
              <div class="text-center">
                <button type="button" class="btn btn-primary" onclick="window.location.href='login.php'">Login</button>
                <p>OR</p>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='register.php'">Register</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="hidden">
    <h1>Embark on Your Japanese Language Journey
      <hr>
      <p>Explore the beauty of the Japanese language through interactive quiz lessons and master vocabulary effortlessly.</p>
    </h1>
  </section>
  <br>
  <section class="hidden">
    <h2>Why Choose Our Quiz Lessons?
      <hr>
      <p>Our quiz lessons offer an engaging and effective way to learn Japanese. Test <br>your knowledge, reinforce concepts, and track your progress with each quiz.</p>
    </h2>
  </section>

  <section class="hidden">
    <h2>Get Started on Your Japanese Journey
      <hr>
      <p>Ready to dive into the world of Japanese language learning? Begin your adventure with these interactive quiz lessons:</p>
      <a href="Quiz.php" style="font-size: 0.7cm; color: blue; text-decoration: none;">- QUIZ LESSONS</a>
      <p>Start your learning journey today and experience the joy of mastering Japanese through quizzes.</p>
    </h2>
  </section>

  <section class="hidden">
    <h2>Contact Us
      <hr>
      <p>If you have any questions or need assistance, feel free to <a href="contact.php">contact us</a>. We're here to help you on your journey to Japanese fluency.</p>
    </h2>
  </section>
  <script src="https://cdn.botpress.cloud/webchat/v1/inject.js"></script>
  <script src="https://mediafiles.botpress.cloud/060f0906-9af4-46d0-a674-6c82602fa376/webchat/config.js" defer></script>
  <!--index page style code-->
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;700&display=swap');

    body {
      background-color: black;
      color: white;
      font-family: 'poppinns', sans-serif;
      padding: 0;
      margin: 0;
      scrollbar-gutter: stable;

    }

    /* scroll bar hiden */
    body::-webkit-scrollbar {
      display: none;
    }

    p {
      font-size: 19px;
      line-height: 1.5;
      margin: 5px 0;
      padding: 0;
    }


    section {
      display: grid;
      place-items: center;
      align-items: center;
      min-height: 87vh;
    }

    .hidden {
      opacity: 0;
      filter: blur(5px);
      transform: translateX(-100%);
      transition: all 2s;
    }

    .show {
      opacity: 1;
      filter: blur(0);
      transform: translateX(0);
    }
  </style>
  <script>
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        console.log(entry)
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
        } else {
          entry.target.classList.remove('show');
        }
      });
    });
    const hiddenElements = document.querySelectorAll('.hidden');
    hiddenElements.forEach((el) => observer.observe(el));
  </script>
</body>

</html>