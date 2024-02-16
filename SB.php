<?php
session_start();
include "include/header.php";
include "config/db.php";
?>

<body>

    <div class="container">
        <h1 class="mb-4">Leaderboard</h1>
        <hr>
        <br>
        <table id="leaderboard" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Rank</th>
                    <th>Profile</th>
                    <th>XP</th>
                    <th>Streak</th> <!-- Added streak column -->
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
                        <td style="font-size: 0.6cm;"><?php echo $row['rank']; ?></td>
                        <td>
                            <img src="uploads/<?php echo $row['image']; ?>" class="rounded-circle" width="50" height="50">
                            <span style="font-size: 0.5cm;"><?php echo $row['name']; ?></span>
                        </td>
                        <td style="font-size: 0.5cm;"><?php echo $row['xp']; ?></td>
                        <td style="font-size: 0.5cm;"><?php echo ($row['streak'] > 0) ? $row['streak'] . '' : 'No streak'; ?>
                         Day   <img class="fire" src="assets/007.gif">
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
                        <!-- Save button with download functionality -->
                        <button type="button" class="btn btn-primary">
                            <a href="image_generator.php" download="JapanEse.jpg" style="color: inherit; text-decoration: none;">Save</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openImageModal() {
                // Open Bootstrap modal with the image
                $('#imageModal').modal('show');
            }
        </script>
    </div>
    <style>
        .fire {
            height:50px;
            width:40px;
        }

        /* scroll bar hiden */
        body::-webkit-scrollbar {
            display: none;
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

        #leaderboard th:first-child,
        #leaderboard td:first-child {
            width: 10%;
            max-width: 10%;
        }
    </style>

</body>

</html>