<?php

    //class for getting cookie for 'remember me' option
    class Cookie{
        //check if cookie exists
        public static function exists($name){
            return (isset($_COOKIE[$name])) ? true : false;
        }
        
        //get cookie
        public static function get($name){
            return $_COOKIE[$name];
        }
        
        //create cookie
        public static function put($name, $value, $expiry){
            if(setcookie($name, $value, time() + $expiry, '/')){
                return true;
            }
            return false;
        }
        
        //delete cookie
        public static function delete($name){
            self::put($name, '', time() -1);
        }
        
    }


?>
