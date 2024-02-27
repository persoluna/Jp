<nav class="navbar navbar-expand-lg navbar-light bg-dark">
  <div class="container-fluid">

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'admin_dash.php') ? 'active-page' : ''; ?>" href="admin_dash.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'quiz.php') ? 'active-page' : ''; ?>" href="quiz.php">All Quiz</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'all_chapters.php') ? 'active-page' : ''; ?>" href="all_chapters.php">All Chapters</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'flashcards.php') ? 'active-page' : ''; ?>" href="flashcards.php">All Flashcards</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'add_chapter.php') ? 'active-page' : ''; ?>" href="add_chapter.php">Add Chapter</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'add_flashcard.php') ? 'active-page' : ''; ?>" href="add_flashcard.php">Add Flashcard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'quiz_lesson.php') ? 'active-page' : ''; ?>" href="quiz_lesson.php">Add Quiz Lesson</a>
        </li>
        <li class="nav-item">
          <form method="post" action="logout.php">
            <button type="submit" name="logout" class="btn btn-outline-primary">Logout</button>
          </form>
        </li>
      </ul>
    </div>

  </div>
</nav>


<style>
  .active-page {
    color: white;
    background-color: blue;
  }

  .navbar {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid #ccc;
  }

  .navbar-brand {
    font-weight: bold;
  }

  .nav-link {
    color: white;
    text-transform: uppercase;
    font-size: 0.9em;
    font-weight: 500;
    letter-spacing: 0.05em;
    transition: color 0.3s;
  }

  .nav-link:hover {
    color: #007bff !important;
  }

  .navbar-toggler {
    border: none !important;
    padding: 6px 8px !important;
    font-size: 16px;
  }

  @media (min-width: 992px) {

    .navbar-expand-lg .navbar-nav .nav-link {
      padding-right: 2rem;
      padding-left: 2rem;
    }

  }
</style>