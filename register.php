<?php
    require_once 'core/init.php';

        //validate form fields
        if(Input::exists()){
            // check for token
            if(Token::check(Input::get('token'))){
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                //validate name to check if it exists and is between 2 and 50 characters long
                'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
                //validate email is between 2 and 50 characters long
                'email' => array(
                'required' => true,
                'min' => 2,
                'max' => 50,
            ), //validate phone number is between 2 and 30 characters long
                'phone' => array(
                'required' => true,
                'min' => 2,
                'max' => 30,
            ), //validate username to check if it exists, is between 2 and 20 characters long and is unique in database
            'username' => array(
                'required' => true,
                'name' => 'Username', // use for validation
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ), //validate password to check if it exists and is over 5 characters
                'password' => array(
                'required' => true,
                'min' => 5
            ), //validate password again to check if it matches initial password
                'password_again' => array(
                'required' => true,
                'matches' => 'password'
            )
            ));

            // if validation is passed insert new user details into database using create and hash/salt password    
            if($validation->passed()){
                $user = new User();
                $salt = Hash::salt(32);
                
                try{
                    $user->create(array(
                        'name' => Input::get('name'),
                        'email' => Input::get('email'),
                        'phone' => Input::get('phone'),
                        'username' => Input::get('username'),
                        'password' => Hash::make(Input::get('password'), $salt),
                        'salt' => $salt,
                        'joined' => date('Y-m-d H:i:s'),
                        'group' => 1,
                        'score' => 0
                    ));
                    
                    Session::flash('home', 'You have been registered and can now log in');
                    Redirect::to('index.php');
                    
                } catch(Exception $e){
                    die($e->getMessage()); 
                }
            } else{
                //output error
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
        <title>Altern8 Reality Register Page</title>
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
                    <a href="#registerPanel" class="ui-icon-info ui-btn-icon-right"></a>
                </div>
            </div>

            <!-- content for slide out menu -->
            <div data-role="panel" data-position="right" data-display="push" data-theme="none" id="registerPanel">
                <div id="registerSlideOut">
                    <p>Info for register page here</p>
                </div>
            </div>

            <div data-role="main" class="ui-content">
                <div id="Register">
                    <h1>REGISTER</h1>


                    <form action="" method="post" name="registerForm">

                        <input type="text" name="name" id="name" placeholder="name" value="<?php  echo escape(Input::get('name')); ?>" required>
                        <input type="email" name="email" id="email" placeholder="email" required>
                        <input type="text" name="phone" id="phone" placeholder="phone number" required>
                        <br />
                        <input type="text" name="username" id="username" placeholder="choose a username" value="<?php  echo escape(Input::get('username')); ?>" required>
                        <input type="password" name="password" id="password" placeholder="choose a password" required>
                        <input type="password" name="password_again" id="password_again" placeholder="confirm password" required>

                        <!-- hidden generated token for user for extra security -->
                        <input type="hidden" name="token" value="<?php echo Token::generate();   ?>">

                        <button class="primaryBtn" type="submit">register</button>

                    </form>
                </div>

            </div>

        </div>
    </body>

    </html>
