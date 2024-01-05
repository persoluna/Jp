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

        <!-- Add the share button with JavaScript to open a Bootstrap modal -->
        <button onclick="openImageModal()" class="btn btn-primary">Share</button>

        <!-- Bootstrap Modal for Image Popup -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Shared Image</h5>
                    </div>
                    <div class="modal-body">
                        <!-- Image content goes here -->
                        <img src="image_generator.php" class="img-fluid" alt="Generated Image">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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