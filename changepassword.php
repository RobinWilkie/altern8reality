<?php

// include the initialisation file
    require_once 'core/init.php';

    $user = new User();

    if(!$user->isLoggedIn()){
        Redirect::to('index.php');
    }

    // check if fields are completed
    if(Input::exists()){
        // validate and check for cross site request forgery
        if(Token::check(Input::get('token'))){
            
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'password_current' => array(
                'required' => true,
                'min' => 5
            ),
                'password_new' => array(
                'required' => true,
                'min' => 5
            ),
                'password_new_again' => array(
                'required' => true,
                'min' => 5,
                'matches' => 'password_new'
            )
            
            ));
            
            if($validation->passed()){
                if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password){
                    echo 'current password wrong'; // let user know if they have inputted wrong current password
                } else {
                    //change password
                    $salt = Hash::salt(32);
                    $user->update(array(
                        'password' => Hash::make(Input::get('password_new'), $salt),
                        'salt' => $salt
                    
                    ));
                    
                    Session::flash('home', 'Your password has been changed');
                    Redirect::to('options.php');
                }
            } else {
                 foreach($validation->errors() as $error){
                    echo $error, '<br />';
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
        <title>Altern8 Reality Password Recovery Page</title>
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
                <div class="headerOne">
                    <a href="profile.php?user=<?php echo escape($user->data()->username); ?>" class="ui-icon-arrow-l ui-btn-icon-left" data-transition="flip"></a>
                    <h2>ALTERN<span>8</span>REALITY</h2>
                    <a href="#passwordPanel" class="ui-icon-info ui-btn-icon-right"></a>
                </div>
            </div>

            <!-- content for slide out menu -->
            <div data-role="panel" data-position="right" data-display="push" data-theme="none" id="passwordPanel">
                <div id="passwordSlideOut">
                    <p>Enter your current password then your new one. Password must be over 5 characters long.</p>
                </div>
            </div>

            <div data-role="main" class="ui-content">
                <div class="centreWrap">
                    <h1>PASSWORD<br>RECOVERY</h1>

                    <form action="" method="post">
                        <div class="field">
                            <label for="password_current">Current Password</label>
                            <input type="password" name="password_current" id="password_current">
                        </div>

                        <div class="field">
                            <label for="password_new">New Password</label>
                            <input type="password" name="password_new" id="password_new">
                        </div>

                        <div class="field">
                            <label for="password_new_again">Retype New Password</label>
                            <input type="password" name="password_new_again" id="password_new_again">
                        </div>

                        <button class="primaryBtn" type="submit">Change</button>
                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

                    </form>
                </div>
            </div>
        </div>
    </body>

    </html>
