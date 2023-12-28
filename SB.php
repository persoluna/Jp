<?php
session_start();
include "include/header.php";
include "config/db.php";
?>

<body>

    <div class="container">
        <h1 class="mb-4">Leaderboard</h1>

        <table id="leaderboard" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>XP</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $sql = "SELECT u.name, uxp.xp, (@rownum := @rownum + 1) AS rank 
                        FROM user u
                        JOIN user_xp uxp ON u.id = uxp.user_id
                        CROSS JOIN (SELECT @rownum := 0) AS init
                        ORDER BY uxp.xp DESC
                        LIMIT 10";

                $result = mysqli_query($con, $sql);

                while ($row = mysqli_fetch_assoc($result)) :
                ?>
                    <tr>
                        <td><?php echo $row['rank']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['xp']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Add the share button with JavaScript to open a popup window -->
        <button onclick="shareImage()" class="btn btn-primary">Share</button>

        <script>
            function shareImage() {
                // Open a new window with the image URL
                var imageUrl = 'image_generator.php';
                window.open(imageUrl, 'ImagePopup', 'width=400, height=400');
            }
        </script>
    </div>
    <style>
        #leaderboard {
            width: 100%;
        }

        th,
        td {
            text-align: center;
        }
    </style>
</body>

</html>