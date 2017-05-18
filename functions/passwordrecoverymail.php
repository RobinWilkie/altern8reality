<?php

date_default_timezone_set('Etc/UTC');

require 'PHPMailer/PHPMailerAutoload.php';
require_once 'core/init.php';

if(!class_exists('PHPMailer')) {
    require('phpmailer/class.phpmailer.php');
	require('phpmailer/class.smtp.php');
}

$mail = new PHPMailer();

$emailBody = "<div>" . $user["username"] . ",<br><br><p> Your password is " . $user["password"] . "<br>Please go to altern8reality/changepassword.php in order to change password<br>Regards,<br> Admin.</p></div>";

$mail->IsSMTP();
$mail->SMTPDebug = 0;
$mail->SMTPAuth = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port     = 587;  
$mail->Username = "robinwilkie80@gmail.com";
$mail->Password = "password"; // insert password here
$mail->Host     = "smtp.gmail.com";
$mail->Mailer   = "smtp";

$mail->SetFrom("robinwilkie80@gmail.com", "Robin Wilkie");
$mail->AddReplyTo("admin@admin.com", "Admin");
$mail->AddAddress($user["email"]);
$mail->Subject = "Altern8reality Password Recovery";		
$mail->MsgHTML($emailBody);
$mail->IsHTML(true);

if(!$mail->Send()) {
	$error_message = 'Problem in Sending Password Recovery Email';
} else {
	$success_message = 'Please check your email to see your password!';
}

?>
