<?php

include_once('dbMysql.php');
$con = new database_con();
$table = "users";
$res=$con->selectusers($table);

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Robin Wilkie -->
        <!-- HND Interactive Media 2016/17 -->
        <!-- Altern8 Reality AR Game -->
        <title>Altern8 Reality Admin Users Page</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta NAME="ROBOTS" CONTENT="INDEX, NOFOLLOW">
        <link type="text/plain" rel="author" href="humans.txt" />
        <link rel="icon" href="../images/a8icon.gif" type="image/gif" sizes="16x16">
        <link href="https://fonts.googleapis.com/css?family=Raleway%7CUbuntu" rel="stylesheet">
        <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
        <link rel="stylesheet" href="../css/styles.css">
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
        <!-- functions to show alerts warning admin if they want to edit or delete user -->
        <script type="text/javascript">
            function del_id(id) {
                if (confirm('Are you sure you want to delete this user?')) {
                    window.location = 'deleteusers.php?delete_id=' + id
                }
            }

            function edit_id(id) {
                if (confirm('Are you sure you want to edit this user?')) {
                    window.location = 'editusers.php?edit_id=' + id
                }
            }

        </script>
    </head>

    <body>
        <div data-role="page" class="content">
            <div data-role="header">
                <div class="headerThree">
                    <a href="admin.php" class="ui-icon-arrow-l ui-btn-icon-left" data-transition="flip"></a>
                    <h2>ALTERN<span>8</span>REALITY</h2>
                    <a href="#myPanel" class="ui-icon-bars ui-btn-icon-right" data-transition="flip"></a>
                </div>
            </div>

            <!-- content for slide out menu -->
            <div data-role="panel" data-position="right" data-display="push" data-theme="none" id="myPanel">
                <div class="slideOut">
                    <a href="../options.php">Main Menu</a>
                    <a href="../game.php" rel="external">Play Game</a>
                    <a href="../howtoplay.php">How To Play</a>
                    <a href="../scoreboard.php">Scoreboard</a>
                    <a href="../about.php">About the Game</a>
                    <a href="../logout.php">Logout</a>
                </div>
            </div>

            <div data-role="main" class="ui-content">
                <h1>USERS</h1>

                <!-- responsive table using JQuery Mobile -->
                <table data-role="table" class="ui-responsive">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Username</th>
                            <th>Joined</th>
                            <th>Score</th>
                            <th>edit/delete</th>
                        </tr>
                    </thead>

                    <?php
                    // fetch results for rows
                     while($row=mysqli_fetch_row($res))
                     {
                    ?>

                        <tbody>
                            <tr>
                                <td>
                                    <?php echo $row[1]; ?>
                                    <!-- show name row -->
                                </td>
                                <td>
                                    <?php echo $row[2]; ?>
                                    <!-- show email row -->
                                </td>
                                <td>
                                    <?php echo $row[3]; ?>
                                    <!-- show phone number row -->
                                </td>
                                <td>
                                    <?php echo $row[4]; ?>
                                    <!-- show username row -->
                                </td>
                                <td>
                                    <?php echo $row[7]; ?>
                                    <!-- show when user joined row -->
                                </td>
                                <td>
                                    <?php echo $row[9]; ?>
                                    <!-- show score row -->
                                </td>

                                <!-- buttons to link to 'edit users' page and 'delete users' page -->
                                <td>
                                    <a href="javascript:edit_id(<?php echo $row[0]; ?>)" data-role="button" data-transition="flip" class="primaryBtn">EDIT</a>

                                    <a href="javascript:del_id(<?php echo $row[0]; ?>)" data-role="button" data-transition="flip" class="primaryBtn">DELETE</a>
                                </td>
                            </tr>
                            <?php
                             }
                             ?>
                        </tbody>
                </table>
                <a href="adduser.php" rel="external" data-role="button" data-transition="flip" class="primaryBtn">ADD A NEW USER</a>
            </div>
        </div>
    </body>

    </html>
