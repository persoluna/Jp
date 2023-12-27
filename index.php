<?php
session_start();
include('include/header.php');

if (!isset($_SESSION['user_id'])) {
  // User is not logged in, trigger the modal
  echo '<script type="text/javascript">$(document).ready(function() { $("#loginModal").modal("show"); });</script>';
}
?>

<body>

  <?php

  if (isset($_SESSION['user_id'])) {

    // User logged in

    echo '<script src="activity_tracking.js"></script>';

    // Get user id
    $userId = $_SESSION['user_id'];

    echo <<<EOT
    <script>
      var userId = $userId;
      updateStatusOnPageLoad(userId);  
      updateStatusOnPageUnload(userId);
    </script>  
EOT;
  }
  ?>

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

  <div class="hero-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>Embark on Your Japanese Language Journey</h1>
          <p>Explore the beauty of the Japanese language through interactive quiz lessons and master vocabulary effortlessly.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="benefits-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2>Why Choose Our Quiz Lessons?</h2>
          <p>Our quiz lessons offer an engaging and effective way to learn Japanese. Test your knowledge, reinforce concepts, and track your progress with each quiz.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="cta-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2>Get Started on Your Japanese Journey</h2>
          <p>Ready to dive into the world of Japanese language learning? Begin your adventure with these interactive quiz lessons:</p>
          <ol>
            <li><a href="Quiz.php">- QUIZ LESSONS</a></li>
          </ol>
          <p>Start your learning journey today and experience the joy of mastering Japanese through quizzes.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="about-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2>About Us</h2>
          <p>At Flash Cards, we are dedicated to providing a unique and interactive Japanese language learning experience. Learn more about our mission and values.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="contact-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2>Contact Us</h2>
          <p>If you have any questions or need assistance, feel free to <a href="contact.php">contact us</a>. We're here to help you on your journey to Japanese fluency.</p>
        </div>
      </div>
    </div>
  </div>

</body>

</html>