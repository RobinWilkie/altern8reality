<?php

    // include the initialisation file
    require_once 'core/init.php';

    // use logout function from User class to terminate this session and redirect to index page
    $user = new User();
    $user->logout();

    Redirect::to('index.php');

?>
