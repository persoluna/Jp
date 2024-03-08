<?php
session_start();
include('include/header.php');

if (!isset($_SESSION['user_id'])) {
  // User is not logged in, trigger the modal
  echo '<script type="text/javascript">$(document).ready(function() { $("#loginModal").modal("show"); });</script>';
}
?>

<body>
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

  <div class="w3-content" style="max-width:2000px;margin-top:0px">

    <!-- Automatic Slideshow Images -->
    <div class="mySlides w3-display-container w3-center">
      <img src="assets/Japan/008.jpg" style="width:100%">
      <div class="w3-display-bottommiddle w3-container w3-text-white w3-padding-32 w3-hide-small">
        <h3>Japanese JLPT Preparation</h3>
        <p><b>Practice our Quiz lessons</b></p>
      </div>
    </div>
    <div class="mySlides w3-display-container w3-center">
      <img src="assets/Japan/004.jpg" style="width:100%">
      <div class="w3-display-bottommiddle w3-container w3-text-white w3-padding-32 w3-hide-small">
        <h3>Learn new words from chapters</h3>
        <p><b>With a big collection of Flashcards to remember all the words for Quiz Lessons</b></p>
      </div>
    </div>
    <div class="mySlides w3-display-container w3-center">
      <img src="assets/Japan/006.jpg" style="width:100%">
      <div class="w3-display-bottommiddle w3-container w3-text-white w3-padding-32 w3-hide-small">
        <h3>Scoreboard</h3>
        <p><b>Gain more XP to be the top 10 Learners on the scoreBoard</b></p>
      </div>
    </div>

    <!-- The Band Section -->
    <div class="w3-container w3-content w3-center w3-padding-60" style="max-width:800px" id="band">
      <h2 class="w3-wide">Japanese Hub</h2>
      <p class="w3-opacity"><i>We love japanese</i></p>
      <p class="w3-justify">We have created a fun and interactive Japanese language learning website where enthusiasts like you can embark on an exciting journey into the heart of Japanese culture and language. At our platform, learning Japanese transcends mere study; it becomes an immersive experience that captivates your senses and ignites your passion for linguistic exploration. Dive into our meticulously crafted chapters, where each lesson is a gateway to understanding the intricacies of Japanese grammar, vocabulary, and culture. With engaging quizzes and dynamic flashcards, our platform ensures that learning Japanese is not just educational but also enjoyable. Whether you're preparing for the JLPT test or simply indulging in the joy of mastering a new language, our platform offers a welcoming space where your progress is celebrated. Challenge yourself and climb the ranks on our scoreboard, where the top 10 highest XP players shine brightly, showcasing their dedication and mastery of Japanese. Join our vibrant community of learners and educators, where sharing knowledge and fostering connections are at the core of our mission. Embrace the true essence of Japanese as you immerse yourself in our innovative learning environment. Let our platform be your guide as you navigate the rich tapestry of the Japanese language and culture. Welcome to a world where learning Japanese is not just a task but a delightful adventure waiting to unfold.</p>
      <div class="w3-row w3-padding-32">
        <h2 class="w3-wide w3-center">THE TEAM</h2>
        <div class="w3-third" style="margin-top: 90px;">
          <img src="assets/japan/amar.png" class="w3-round w3-margin-bottom" alt="Random Name" style="width:60%">
          <p><u>Amar Singh</u></p>
        </div>
        <div class="w3-third" style="margin-left: 210px;">
          <img src="assets/japan/shakib.png" class="w3-round w3-margin-bottom" alt="Random Name" style="width:60%">
          <p><u>Shakib Munshi</u></p>
        </div>
      </div>
    </div>

    <!-- The Tour Section -->
    <div class="w3-black" id="tour">
      <div class="w3-container w3-content w3-padding-64" style="max-width:800px">
        <h2 class="w3-wide w3-center">TOUR DATES</h2>
        <p class="w3-opacity w3-center"><i>Remember to book your tickets!</i></p><br>

        <ul class="w3-ul w3-border w3-white w3-text-grey">
          <li class="w3-padding">September <span class="w3-tag w3-red w3-margin-left">Sold out</span></li>
          <li class="w3-padding">October <span class="w3-tag w3-red w3-margin-left">Sold out</span></li>
          <li class="w3-padding">November <span class="w3-badge w3-right w3-margin-right">3</span></li>
        </ul>

        <div class="w3-row-padding w3-padding-32" style="margin:0 -16px">
          <div class="w3-third w3-margin-bottom">
            <img src="/w3images/newyork.jpg" alt="New York" style="width:100%" class="w3-hover-opacity">
            <div class="w3-container w3-white">
              <p><b>New York</b></p>
              <p class="w3-opacity">Fri 27 Nov 2016</p>
              <p>Praesent tincidunt sed tellus ut rutrum sed vitae justo.</p>
              <button class="w3-button w3-black w3-margin-bottom" onclick="document.getElementById('ticketModal').style.display='block'">Buy Tickets</button>
            </div>
          </div>
          <div class="w3-third w3-margin-bottom">
            <img src="/w3images/paris.jpg" alt="Paris" style="width:100%" class="w3-hover-opacity">
            <div class="w3-container w3-white">
              <p><b>Paris</b></p>
              <p class="w3-opacity">Sat 28 Nov 2016</p>
              <p>Praesent tincidunt sed tellus ut rutrum sed vitae justo.</p>
              <button class="w3-button w3-black w3-margin-bottom" onclick="document.getElementById('ticketModal').style.display='block'">Buy Tickets</button>
            </div>
          </div>
          <div class="w3-third w3-margin-bottom">
            <img src="/w3images/sanfran.jpg" alt="San Francisco" style="width:100%" class="w3-hover-opacity">
            <div class="w3-container w3-white">
              <p><b>San Francisco</b></p>
              <p class="w3-opacity">Sun 29 Nov 2016</p>
              <p>Praesent tincidunt sed tellus ut rutrum sed vitae justo.</p>
              <button class="w3-button w3-black w3-margin-bottom" onclick="document.getElementById('ticketModal').style.display='block'">Buy Tickets</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Ticket Modal -->
    <div id="ticketModal" class="w3-modal">
      <div class="w3-modal-content w3-animate-top w3-card-4">
        <header class="w3-container w3-teal w3-center w3-padding-32">
          <span onclick="document.getElementById('ticketModal').style.display='none'" class="w3-button w3-teal w3-xlarge w3-display-topright">×</span>
          <h2 class="w3-wide"><i class="fa fa-suitcase w3-margin-right"></i>Tickets</h2>
        </header>
        <div class="w3-container">
          <p><label><i class="fa fa-shopping-cart"></i> Tickets, $15 per person</label></p>
          <input class="w3-input w3-border" type="text" placeholder="How many?">
          <p><label><i class="fa fa-user"></i> Send To</label></p>
          <input class="w3-input w3-border" type="text" placeholder="Enter email">
          <button class="w3-button w3-block w3-teal w3-padding-16 w3-section w3-right">PAY <i class="fa fa-check"></i></button>
          <button class="w3-button w3-red w3-section" onclick="document.getElementById('ticketModal').style.display='none'">Close <i class="fa fa-remove"></i></button>
          <p class="w3-right">Need <a href="#" class="w3-text-blue">help?</a></p>
        </div>
      </div>
    </div>

    <!-- The Contact Section -->
    <div class="w3-container w3-content w3-padding-64" style="max-width:800px" id="contact">
      <h2 class="w3-wide w3-center">CONTACT US</h2>
      <p class="w3-opacity w3-center"><i>Drop a note!</i></p>
      <div class="w3-row w3-padding-32">
        <div class="w3-col m6 w3-large w3-margin-bottom">
          <i class="fa fa-map-marker" style="width:30px"></i> Vapi, India<br><br>
          <i class="fa fa-phone" style="width:30px"></i> Phone: <br> +91 6356929824 <br>
          +91 9173366418
          <br><br>
          <i class="fa fa-envelope" style="width:30px"> </i> Email: shakibshakilahmed@gmail.com
          amarsingh@gmail.com<br>
        </div>
        <div class="w3-col m6">
          <form action="/action_page.php" target="_blank">
            <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
              <div class="w3-half">
                <input class="w3-input w3-border" type="text" placeholder="Name" required name="Name">
              </div>
              <div class="w3-half">
                <input class="w3-input w3-border" type="text" placeholder="Email" required name="Email">
              </div>
            </div>
            <input class="w3-input w3-border" type="text" placeholder="Message" required name="Message">
            <button class="w3-button w3-black w3-section w3-right" type="submit">SEND</button>
          </form>
        </div>
      </div>
    </div>

    <!-- End Page Content -->
  </div>

  <!-- Image of location/map -->
  <img src="assets/japan/004.gif" class="w3-image w3-greyscale-min" style="width:100%">

  <!-- Footer -->
  <footer class="w3-container w3-padding-64 w3-center w3-opacity w3-light-grey w3-xlarge">
    <i class="fa fa-facebook-official w3-hover-opacity"></i>
    <i class="fa fa-instagram w3-hover-opacity"></i>
    <i class="fa fa-snapchat w3-hover-opacity"></i>
    <i class="fa fa-linkedin w3-hover-opacity"></i>
  </footer>
  <script src="https://cdn.botpress.cloud/webchat/v1/inject.js"></script>
  <script src="https://mediafiles.botpress.cloud/060f0906-9af4-46d0-a674-6c82602fa376/webchat/config.js" defer></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;700&display=swap');

    body::-webkit-scrollbar {
      display: none;
    }
  </style>

  <script>
    // Automatic Slideshow - change image every 4 seconds
    var myIndex = 0;
    carousel();

    function carousel() {
      var i;
      var x = document.getElementsByClassName("mySlides");
      for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
      }
      myIndex++;
      if (myIndex > x.length) {
        myIndex = 1
      }
      x[myIndex - 1].style.display = "block";
      setTimeout(carousel, 6000);
    }

    // Used to toggle the menu on small screens when clicking on the menu button
    function myFunction() {
      var x = document.getElementById("navDemo");
      if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
      } else {
        x.className = x.className.replace(" w3-show", "");
      }
    }

    // When the user clicks anywhere outside of the modal, close it
    var modal = document.getElementById('ticketModal');
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

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