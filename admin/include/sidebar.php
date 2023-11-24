<!-- Sidebar -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="admin_dash.php">
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="all_chapters.php">
                    All Chapters
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="flashcard/flashcards.php">
                    All Flashcards
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_chapter.php">
                    Add chapter
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="flashcard/add_flashcard.php">
                    Add flashcard
                </a>
            </li>
            <!-- Add more sidebar items as needed -->
        </ul>
    </div>
</nav>

<style>
    /* Custom CSS for the sidebar and navbar shadows */
    #sidebar {
        height: 100vh;
        /* Set the height to 100% of the viewport height */
        border-right: 1px solid #ddd;
        padding-top: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .navbar {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* Add shadow to the navbar */
    }

    .nav-link {
        color: black !important;
        /* Set the text color to black */
    }

    .nav-link:hover {
        background-color: #f8f9fa !important;
        /* Change background color on hover */
    }

    .nav-item {
        background-color: cornflowerblue;
        margin-bottom: 10px;
        /* Add some bottom margin to each item */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* Add shadow to each item */
        border-radius: 8px;
        /* Add border-radius for rounded corners */
    }

    /* Optional: Increase padding for a better visual appearance */
    .nav-item a {
        padding: 10px;
        display: block;
    }
</style>