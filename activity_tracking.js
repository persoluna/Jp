// activity_tracking.js

function sendActivityUpdate(userId) {
    var newStatus = document.hidden ? 'Inactive' : 'Active';

    // Send AJAX request to update activity_status
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'update_user_activity_status.php?userId=' + userId + '&activityStatus=' + newStatus, true);
    xhr.send();
}

function startHeartbeat(userId) {
    // Heartbeat function
    function heartbeat() {
        sendActivityUpdate(userId);

        // Scheduling the next heartbeat after a certain interval
        setTimeout(heartbeat, 15000); // 15 seconds
    }

    // Start the heartbeat when the page is loaded
    document.addEventListener('DOMContentLoaded', function () {
        heartbeat();
    });

    // Listen for visibility change
    document.addEventListener('visibilitychange', function () {
        sendActivityUpdate(userId);
    });
}
