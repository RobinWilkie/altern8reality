<?php

//create a class for Session
class Session{
    public static function exists($name){
        return (isset($_SESSION[$name])) ? true : false;
        
    }
    
    //returns the value of the session
    public static function put($name, $value){
        return $_SESSION[$name] = $value; 
    }
    
    // to get session
    public static function get($name){
        return $_SESSION[$name];
    }
    
    // to delete session
    public static function delete($name){
        if(self::exists($name)){
            unset($_SESSION[$name]);
        }
    }
    //flash data to stop repeated messages appearing in a single session
    public static function flash($name, $string = ''){
        if(self::exists($name)){
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else{
            self::put($name, $string);
        }
    }
}

?>