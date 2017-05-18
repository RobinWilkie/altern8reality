<?php
// initialisation file that is included on each page the user or admin will visit when logged in
// this will define starting sessions, the config file and auto loading the classes

// call the session start so users can log in
session_start();

// create configuration settings as global variable
// this consists of arrays for the mysql connection, the cookies and how long they last for and for sessions

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1', // loopback address - a non-routable IP address that is defined as referring to the "local" computer
        'username' => 'root',
        'password' => '',
        'db' => 'altern8reality'
    ), // array for mysql settings
    
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 86400 // this is 24 hours in seconds and is how long the cookie lasts for
    ), // array for cookie details and expiry 
    
    'session' => array(
        'session_name' => 'user', // array for session name and token
        'token_name' => 'token') 
);

// autoload in a class from the 'classes' folder as and when it is required
spl_autoload_register(function($class){
    require_once 'classes/' . $class . '.php';
}); 

// this will include files from functions directory
// require the sanitize file to sanitize data going into database
require_once 'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
    
    if($hashCheck->count()){
        $user = new User($hashCheck->first()->user_id); // find user data
        $user->login(); // login user
    }
}



?>
