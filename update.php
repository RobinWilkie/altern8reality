<?php

    // php to let users update their details

    // include the initialisation file
    require_once 'core/init.php';

    $user = new User();

    // if user is not logged in redirect them to profile page
    if(!$user->isLoggedIn()){
        Redirect::to('profile.php');
    }

    // validate new input
    if(Input::exists()){
        if(Token::check(Input::get('token'))){
            //validate if token exists
            $validate = new Validate();
            // for each field validate that there is content and is between 2 and 50 characters long
            $validation = $validate->check($_POST, array(
                'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50 
               ),
                'phone' => array(
                'required' => true,
                'min' => 2,
                'max' => 50 
               ),
                'email' => array(
                'required' => true,
                'min' => 2,
                'max' => 50 
               ),
                'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 50 
               )
            ));
            
            // if validation passes update database with new input
            if($validate->passed()){
                try{
                    $user->update(array(
                        'name' => Input::get('name'),
                        'phone' => Input::get('phone'),
                        'email' => Input::get('email'),
                        'username' => Input::get('username')
                    ));
                    
                    // after details have been updated redirect user to the main options page
                    Session::flash('home', 'Your details have been updated');
                    Redirect::to('options.php');
                    
                } catch(Exception $ex){
                    die($ex->getMessage());
                }
            } else{
                foreach($validate->errors() as $error){
                    echo $error, '<br>';
                }
            }
        }
    }

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Robin Wilkie -->
        <!-- HND Interactive Media 2016/17 -->
        <!-- Altern8 Reality AR Game -->
        <title>Altern8 Reality Profile Page</title>
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
            <!-- content for slide out menu utilising JQuery Mobile -->
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
                <!-- form for updating user info - php gets the current info and shows it in the appropriate field -->
                <form action="" method="post">
                    <div class="field centreWrap">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" value="<?php echo escape($user->data()->name); ?>">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" value="<?php echo escape($user->data()->phone); ?>">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" value="<?php echo escape($user->data()->email); ?>">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" value="<?php echo escape($user->data()->username); ?>">

                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

                        <button class="primaryBtn" type="submit">Update Details</button>

                        <!-- seperate link for changing password -->
                        <p>want to change your password? <a href="changepassword.php" data-transition="flip">click here</a></p>
                    </div>
                </form>
            </div>

        </div>

    </body>

    </html>
