<?php

    // redirecting function to change pages and includes 404 page if page does not exist

    class Redirect{
        
        public static function to($location = null){
            if($location){
                // if redirecting to a path this will never be numeric so switch page location to 404 page
                if(is_numeric($location)){
                    switch($location){
                        case 404:
                            header('HTTP/1.0 404 Not Found');
                            include '../includes/errors/404.php';
                            exit();
                        break;
                }
            }
                header('Location: ' . $location);
                exit();
            }
        }
    }

?>
