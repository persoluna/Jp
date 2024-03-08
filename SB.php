<?php
session_start();
include "include/header.php";
include "config/db.php";
?>

<body>

    <div class="container">
        <h1 class="mb-4" style="color: black; margin-top: 15px;">Leaderboard</h1>
        <hr>
        <br>
        <table id="leaderboard" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr class="table table-dark">
                    <th class="rank-header">Rank</th>
                    <th class="profile-header">Profile</th>
                    <th class="points-header">Points</th>
                    <th class="streak-header">Streak</th> <!-- Added streak column -->
                </tr>
            </thead>

            <tbody>
    <?php
    $sql = "SELECT u.name, u.image, uxp.xp, us.total_days AS streak, (@rownum := @rownum + 1) AS rank 
            FROM user u
            JOIN user_xp uxp ON u.id = uxp.user_id
            LEFT JOIN user_streak us ON u.id = us.user_id
            CROSS JOIN (SELECT @rownum := 0) AS init
            ORDER BY uxp.xp DESC
            LIMIT 10";

    $result = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($result)) :
    ?>
        <tr>
            <td class="rank"><?php echo $row['rank']; ?></td>
            <td class="profile">
                <img src="uploads/<?php echo $row['image']; ?>" class="rounded-circle" width="50" height="50">
                <span class="profile-name"><?php echo $row['name']; ?></span>
            </td>
            <td class="points"><?php echo $row['xp']; ?></td>
            <td class="streak">
                <?php
                if ($row['streak'] > 0) {
                    echo $row['streak'] . ' Day';
                    echo '<img class="fire" src="assets/007.gif">';
                } else {
                    echo '0 Day';
                    echo '<img class="zero-streak" src="assets/snow-winter.gif">';
                }
                ?>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>

        </table>

        <!-- Add the share button with JavaScript to open a Bootstrap modal -->
        <button onclick="openImageModal()" class="btn btn-primary">Share</button>

        <!-- Enhanced Bootstrap Modal for Image Popup -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="imageModalLabel">Share Image</h5>
                    </div>
                    <div class="modal-body">
                        <!-- Image content goes here -->
                        <img src="image_generator.php" class="img-fluid rounded" alt="Generated Image">
                    </div>
                    <div class="modal-footer">
                        <div class="container">
                            <button class="share-btn">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <div class="share-options">
                                <p class="title">share</p>
                                <div class="social-media">
                                    <button class="social-media-btn"><i class="far fa-folder-open"></i></button>
                                    <button class="social-media-btn"><i class="fab fa-whatsapp"></i></button>
                                    <button class="social-media-btn"><i class="fab fa-instagram"></i></button>
                                    <button class="social-media-btn"><i class="fab fa-twitter"></i></button>
                                    <button class="social-media-btn"><i class="fab fa-facebook-f"></i></button>
                                    <button class="social-media-btn"><i class="fab fa-linkedin-in"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function openImageModal() {
                // Open Bootstrap modal with the image
                $('#imageModal').modal('show');
            }

            const shareBtn = document.querySelector('.share-btn');
            const shareOptions = document.querySelector('.share-options');

            shareBtn.addEventListener('click', () => {
                shareOptions.classList.toggle('active');
            })
        </script>
    </div>
    <style>
        body {
            background-color: #D7E2EE;
        }

        .fire {
            height: 50px;
            width: 40px;
        }

        .zero-streak {
            height: 50px;
            width: 50px;
        }

        #leaderboard {
            width: 100%;
        }

        th,
        td {
            text-align: center;
            font-size: 1.3em;
        }

        .rounded-circle {
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ddd;
        }

        .rank-header {
            width: 10%;
            max-width: 10%;
        }

        .rank {
            font-weight: bold;
            color: orange;
        }

        .profile-header,
        .points-header,
        .streak-header {
            width: 30%;
        }

        .profile-name {
            font-size: 1.1em;
        }

        .streak {
            font-weight: bold;
            color: orange;
        }

        #leaderboard {
            width: 100%;
        }

        th,
        td {
            text-align: center;
        }

        .rounded-circle {
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ddd;
        }

        #leaderboard {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            /* Adjust the second value to increase or decrease spacing */
        }

        #leaderboard th:first-child,
        #leaderboard td:first-child {
            width: 10%;
            max-width: 10%;
        }


        .share-btn {
            position: relative;
            border: none;
            background: #fff;
            color: #ff7d7d;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 30px;
            padding-top: 2.5px;
            padding-right: 3px;
            cursor: pointer;
            z-index: 2;
        }

        .share-options {
            position: center;
            bottom: 50%;
            left: 50%;
            width: auto;
            height: auto;
            transform-origin: bottom left;
            transform: scale(0);
            border-top-left-radius: 20px;
            border-bottom-right-radius: 20px;
            background: rgba(15, 15, 15, 0.5);
            color: #fff;
            padding: 20px;
            font-family: 'roboto';
            transition: .5s;
            transition-delay: .5s;
            ;
        }

        .share-options.active {
            transform: scale(1);
            transition-delay: 0s;
        }

        .title {
            opacity: 0;
            transition: .5s;
            transition-delay: 0s;
            font-size: 20px;
            text-transform: capitalize;
            border-bottom: 1px solid #fff;
            width: fit-content;
            padding: 0 20px 3px 0;
        }

        .social-media {
            opacity: 0;
            transition: .5s;
            transition-delay: 0s;
            width: 250px;
            height: 120px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            margin: 10px 0;
        }

        .social-media-btn {
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #000;
            color: #fff;
            line-height: 50px;
            font-size: 25px;
            cursor: pointer;
            margin: 0 5px;
            text-align: center;
        }

        .social-media-btn:nth-child(1) {
            background: #FFA654;
        }

        .social-media-btn:nth-child(2) {
            background: #25D366;
        }

        .social-media-btn:nth-child(3) {
            background: #E4405F;
        }

        .social-media-btn:nth-child(4) {
            background: #1DA1F2;
        }

        .social-media-btn:nth-child(5) {
            background: #1877F2;
        }

        .social-media-btn:nth-child(6) {
            background: #0A66C2;
        }

        .share-options.active .title,
        .share-options.active .social-media,
        .share-options.active .link-container {
            opacity: 1;
            transition: .5s;
            transition-delay: .5s;
        }

        /* scroll bar hiden */
        body::-webkit-scrollbar {
            display: none;
        }
    </style>
</body>

</html>