function sendActivityUpdate(userId, status) {

  // AJAX request code   
  var xhr = new XMLHttpRequest();
  xhr.open('GET', 'update_user_activity_status.php?userId=' + userId + '&activityStatus=' + status, true);
  xhr.send();

}