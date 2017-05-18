<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Robin Wilkie -->
    <!-- HND Interactive Media 2016/17 -->
    <!-- Altern8 Reality AR Game -->
    <title>Altern8 Reality Index Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta NAME="ROBOTS" CONTENT="INDEX, NOFOLLOW">
    <link type="text/plain" rel="author" href="humans.txt" />
    <link rel="icon" href="images/a8icon.gif" type="image/gif" sizes="16x16">
    <!-- links to Google Fonts and JQuery Mobile CDN hosted files -->
    <link href="https://fonts.googleapis.com/css?family=Raleway%7CUbuntu" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.js" integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA=" crossorigin="anonymous"></script>

    <!-- function to hide buttons on load then fade them in over 3 seconds using JQuery -->
    <script>
        $(document).ready(function() {
            $('#indexOPtions').hide().delay(1000).fadeIn(3000);
        });

    </script>
</head>

<body>
    <div data-role="page" class="content">
        <div data-role="header">
            <!-- header empty on home page -->
        </div>

        <div data-role="main" class="ui-content">
            <!-- spinning cube animation using CSS with altern8reality logo, each div is a panel of the cube -->
            <div class="cubewrap">
                <div class="cube">
                    <div class="front">
                        <h1>ALTERN<br><span>8</span><br>REALITY</h1>
                    </div>
                    <div class="back">
                        <h1>ALTERN<br><span>8</span><br>REALITY</h1>
                    </div>
                    <div class="top">
                        <h1>ALTERN<br><span>8</span><br>REALITY</h1>
                    </div>
                    <div class="bottom">
                        <h1>ALTERN<br><span>8</span><br>REALITY</h1>
                    </div>
                    <div class="left">
                        <h1>ALTERN<br><span>8</span><br>REALITY</h1>
                    </div>
                    <div class="right">
                        <h1>ALTERN<br><span>8</span><br>REALITY</h1>
                    </div>
                </div>
            </div>

            <!-- Button options to login or register -->
            <div id="indexOPtions">
                <a href="login.php" rel="external" data-role="button" data-transition="flip" class="primaryBtn">sign in</a>
                <a href="register.php" rel="external" data-role="button" data-transition="flip" class="secondaryBtn">register</a>
            </div>
        </div>
    </div>

</body>

</html>
