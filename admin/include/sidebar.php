<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="admin_dash.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="quiz.php">All Quiz</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="all_chapters.php">All Chapters</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="flashcards.php">All Flashcards</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add_chapter.php">Add Chapter</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add_flashcard.php">Add Flashcard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="quiz_lesson.php">Add Quiz Lesson</a>
        </li>
        <li>
          <form method="post" action="logout.php">
            <button type="submit" name="logout" class="dropdown-item">Logout</button>
          </form>
        </li>
      </ul>
    </div>

  </div>
</nav>
<style>
  .navbar {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid #ccc;
  }

  .nav-item {
    padding-top: 4px;
    padding-bottom: 4px;
  }

  .navbar-brand {
    font-weight: bold;
  }

  .nav-link {
    text-transform: uppercase;
    font-size: 0.8em;
    font-weight: 500;
    letter-spacing: 0.05em;
    transition: color 0.3s;
  }

  .nav-link:hover {
    color: #fff !important;
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