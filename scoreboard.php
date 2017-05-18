<?php

    // include the initialisation file
    require_once 'core/init.php';


    if(Session::exists('home')){
        echo '<p>' . Session::flash('home') . '</p>';
    }

    $user = new User(); 
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Robin Wilkie -->
        <!-- HND Interactive Media 2016/17 -->
        <!-- Altern8 Reality AR Game -->
        <title>Altern8 Reality Scoreboard Page</title>
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
                <div class="headerThree">
                    <a href="options.php" class="ui-icon-arrow-l ui-btn-icon-left" data-transition="flip"></a>
                    <h2>ALTERN<span>8</span>REALITY</h2>
                    <a href="#myPanel" class="ui-icon-bars ui-btn-icon-right" data-transition="flip"></a>
                </div>
            </div>

            <!-- content for slide out menu using JQuery Mobile -->
            <div data-role="panel" data-position="right" data-display="push" data-theme="none" id="myPanel">
                <div class="slideOut">
                    <a href="options.php">Main Menu</a>
                    <a href="game.php" rel="external">Play Game</a>
                    <a href="howtoplay.php">How To Play</a>
                    <a href="scoreboard.php">Scoreboard</a>
                    <a href="about.php">About the Game</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>

            <div data-role="main" class="ui-content">
                <div id="scoreboard">
                    <h1>SCOREBOARD</h1>
                    <?php

                        // connect to database
                        $con = mysqli_connect("127.0.0.1", "root", "", "altern8reality")
                            or die("Unable to connect");
                    
                    // create div to hold header and high scores table
                    echo "<div align=center>";
                
                    // select names and scores from database, limit the number to 10 and order results by the highest score to lowest
                    // also do not show admin in high score table
                    $mydata = mysqli_query($con, "SELECT username, score FROM users WHERE username != 'admin' ORDER BY score DESC LIMIT 10");
                    
                    echo "<h2>Top Scores</h2>";

                    //create table to hold results
                    echo "<table>
                        <tr>
                            <th>Position</th>
                            <th>Name</th>
                            <th>Score</th>
                        </tr>";

                    // loop through each record and show in table
                    
                    $position = 1; // set first position to 1 then increment number in loop
                    
                    while($records = mysqli_fetch_array($mydata)){
                        echo "<tr>
                            <td>" . $position++ . "</td>
                            <td>{$records['username']}</td>
                            <td>{$records['score']}</td>
                        </tr>";
                    }
                    
                    //close connection
                    mysqli_close($con);

?>


                </div>
            </div>
        </div>

    </body>

    </html>
