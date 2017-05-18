<?php

    // include the initialisation file
    require_once 'core/init.php';
    
    //check if form has been submitted
    if(Input::exists()){
        //check if token exists
        if(Token::check(Input::get('token'))){
            
            //validate username and password given
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'username' => array('required' =>true),
                'password' => array('required' =>true) 
            ));
            
            // if validation is passed create new instance of user
            if($validation->passed()){
               
                $user = new User();
                
                // 'remember me' variable
                $remember = (Input::get('remember') === 'on') ? true : false; // returns true if user selects 'remember me'
                
                // log user in
                $login = $user->login(Input::get('username'), Input::get('password'), $remember); 
                
                if($login){
                    Redirect::to('options.php'); // redirect to the options page if login is successful
                } else{
                    echo 'Login failed'; // error message if login is unsuccessful
                }
            } else{
                foreach($validation->errors() as $error){ 
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
        <title>Altern8 Reality Sign In</title>
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
                    <a href="index.php" class="ui-icon-arrow-l ui-btn-icon-left" data-transition="flip"></a>
                    <h2>ALTERN<span>8</span>REALITY</h2>
                    <a href="#loginPanel" class="ui-icon-info ui-btn-icon-right"></a>
                </div>
            </div>

            <!-- content for slide out menu -->
            <div data-role="panel" data-position="right" data-display="push" data-theme="none" id="loginPanel">
                <div id="loginSlideOut">
                    <p>Enter your username and password here. If you don't have these please go to our register page.</p>
                </div>
            </div>

            <div data-role="main" class="ui-content">
                <div id="SignIn">
                    <h1>SIGN IN</h1>

                    <!-- Sign in form -->
                    <form action="" method="post" name="signInForm">
                        <input type="text" name="username" id="username" placeholder="username" required>
                        <input type="password" name="password" id="password" placeholder="passsword" required>


                        <label for="remember">
                        <input type="checkbox" name="remember" id="remember" checked="">Remember me
                    </label>

                        <!-- hidden token for extra security -->
                        <input type="hidden" name="token" value="<?php echo Token::generate();  ?>">
                        <button class="primaryBtn" type="submit">sign in</button>
                    </form>

                    <hr>

                    <p>forgotten your password? <a href="forgotpassword.php" data-transition="flip">click here</a></p>

                </div>
            </div>

        </div>
    </body>

    </html>
