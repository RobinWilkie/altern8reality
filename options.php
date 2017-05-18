<?php

    // include the initialisation file
    require_once 'core/init.php';


    if(Session::exists('home')){
        echo '<p>' . Session::flash('home') . '</p>';
    }

    $user = new User(); 

    if($user->isLoggedIn()){
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Robin Wilkie -->
        <!-- HND Interactive Media 2016/17 -->
        <!-- Altern8 Reality AR Game -->
        <title>Altern8 Reality Options Page</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta NAME="ROBOTS" CONTENT="INDEX, NOFOLLOW">
        <link type="text/plain" rel="author" href="humans.txt" />
        <link rel="icon" href="images/a8icon.gif" type="image/gif" sizes="16x16">
        <!-- links to Google Fonts and JQuery Mobile CDN hosted files -->
        <link href="https://fonts.googleapis.com/css?family=Raleway%7CUbuntu" rel="stylesheet">
        <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
        <link rel="stylesheet" href="css/styles.css">
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    </head>

    <body>
        <div data-role="page" class="content">
            <div data-role="header">
                <div class="headerTwo">
                    <a href="options.php" class="ui-icon-home ui-btn-icon-left" data-transition="flip"></a>
                    <h2>ALTERN<span>8</span>REALITY</h2>
                    <a href="#myPanel" class="ui-icon-bars ui-btn-icon-right" data-transition="flip"></a>
                </div>
            </div>

            <!-- content for slide out menu -->
            <div data-role="panel" data-position="right" data-display="push" data-theme="none" id="myPanel">
                <div class="slideOut">
                    <a href="options.php">Main Menu</a>
                    <a href="game.php" rel="external">Play Game</a>
                    <a href="howtoplay.php" rel="external">How To Play</a>
                    <a href="scoreboard.php">Scoreboard</a>
                    <a href="about.php">About the Game</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>

            <div data-role="main" class="ui-content">
                <div id="optionsMenu">
                    <a href="game.php" rel="external" data-role="button" data-transition="flip" class="primaryBtn">PLAY</a>
                    <a href="howtoplay.php" rel="external" data-role="button" data-transition="flip" class="secondaryBtn">HOW TO PLAY</a>
                    <a href="scoreboard.php" data-role="button" data-transition="flip" class="secondaryBtn">SCOREBOARD</a>
                    <a href="about.php" data-role="button" data-transition="flip" class="secondaryBtn">ABOUT</a>
                    <?php

                        // check if user has admin permission and only if they do, display ADMIN page button link                        
                        if($user->hasPermission('admin')){
                        echo '<a href="admin/admin.php" data-role="button" data-transition="flip" class="secondaryBtn">ADMIN AREA</a>';
                            }

                        } 
                ?>
                </div>

                <!-- show greeting for user using php to retrieve username from database and options to update info or log out -->
                <div class="greeting">
                    <p>Logged in as
                        <?php  echo escape($user->data()->username); ?>
                        <br>
                        <a href="profile.php?user=<?php echo escape($user->data()->username); ?>">view profile</a> or
                        <a href="logout.php">Logout</a>
                    </p>
                </div>
            </div>
        </div>

    </body>

    </html>
