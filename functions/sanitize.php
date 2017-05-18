<?php

// sanitize data going into the database for user profile changes for security

require_once 'core/init.php';

// create an escape function passing in a string
function escape($string){
    //escape the string passed in, escape double quotes and character encoding
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}


?>
