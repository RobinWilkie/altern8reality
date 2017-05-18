<?php

    // include the initialisation file
    require_once 'core/init.php';

    // if user has submitted form check conditions and activate the password recovery email through PHPMailer
	if(!empty($_POST["forgot-password"])){
		$conn = mysqli_connect("127.0.0.1", "root", "", "altern8reality");
		
        // check conditions for username and email matching database records
		$condition = "";
		if(!empty($_POST["user-login-name"])) 
			$condition = " username = '" . $_POST["user-login-name"] . "'";
		if(!empty($_POST["user-email"])) {
			if(!empty($condition)) {
				$condition = " and ";
			}
			$condition = " email = '" . $_POST["user-email"] . "'";
		}
		
		if(!empty($condition)) {
			$condition = " where " . $condition;
		}

        // use condition in sql statement to get results
		$sql = "Select * from users " . $condition;
		$result = mysqli_query($conn,$sql);
		$user = mysqli_fetch_array($result);
		
        // if user details are found initialise PHPMailer in 'passwordrecoverymail.php' file
		if(!empty($user)) {
			require_once("functions/passwordrecoverymail.php");
		} else {
			$error_message = 'No User Found';
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
        <link href="https://fonts.googleapis.com/css?family=Raleway%7CUbuntu" rel="stylesheet">
        <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
        <link rel="stylesheet" href="css/styles.css">
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
        <script>
            function validate_forgot() {
                if ((document.getElementById("user-login-name").value == "") && (document.getElementById("user-email").value == "")) {
                    document.getElementById("validation-message").innerHTML = "Login name or Email is required!"
                    return false;
                }
                return true
            }

        </script>

    </head>

    <body>

        <div data-role="page" class="content">
            <div data-role="header">
                <div class="headerOne">
                    <a href="login.php" class="ui-icon-arrow-l ui-btn-icon-left" data-transition="flip"></a>
                    <h2>ALTERN<span>8</span>REALITY</h2>
                    <a href="#passwordPanel" class="ui-icon-info ui-btn-icon-right"></a>
                </div>
            </div>

            <!-- content for slide out menu -->
            <div data-role="panel" data-position="right" data-display="push" data-theme="none" id="passwordPanel">
                <div id="passwordSlideOut">
                    <p>Info for password page here</p>
                </div>
            </div>

            <div data-role="main" class="ui-content">
                <div id="passwordRecovery">
                    <h1>PASSWORD<br>RECOVERY</h1>

                    <form action="" method="POST" name="recoveryForm" onSubmit="return validate_forgot();">
                        <input type="text" name="user-login-name" id="user-login-name" class="input-field" placeholder="enter your username">
                        <input type="text" name="user-email" id="user-email" class="input-field" placeholder="enter your email">

                        <a href="#dlg-pwd-reset-sent" data-role="button" data-rel="popup" data-transition="pop" data-position-to="window" class="primaryBtn" id="forgot-password">submit
                        </a>

                        <!-- show message if successful -->
                        <div class="success_message">
                            <?php if(!empty($success_message)) {
                             echo $success_message; 
                             } ?>
                        </div>

                        <!-- show error message if validation fails -->
                        <div id="validation-message">
                            <?php if(!empty($error_message)) {
                             echo $error_message;
                            } ?>
                        </div>
                    </form>
                </div>

                <!-- pop up window using JQuery Mobile alerting user to check their email for password reset instructions -->
                <div data-role="popup" id="dlg-pwd-reset-sent" data-dismissible="false" style="max-width:400px;">
                    <div role="main" class="ui-content">
                        <h3>We've sent you an email</h3>
                        <p>Check your inbox and follow the instructions on the email.</p>
                        <div class="mc-text-center">
                            <a href="index.php" class="ui-btn ui-corner-all ui-shadow ui-btn-b mc-top-margin-1-5">OK</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </body>

    </html>
