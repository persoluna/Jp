// fav_chp.js

function markAsFavorite(event, chapterId) {
    // Prevent the default behavior of the button
    event.preventDefault();

    // Make an AJAX request to mark the chapter as favorite
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'mark_as_favorite.php?chapterId=' + chapterId, true);
    xhr.send();
}

function unmarkAsFavorite(event, chapterId) {
    event.preventDefault(); // Prevent the default behavior of the button

    // Make an AJAX request to unmark the chapter as favorite
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'unmark_as_favorite.php?chapterId=' + chapterId, true);
    xhr.send();
}
