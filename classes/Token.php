<?php
// cross-site request forgery protection

class Token{
    public static function generate(){
        return Session::put(Config::get('session/token_name'), md5(uniqid())); //returns unique token to register for each user
    }
    
    //function to check if token exists and is same as generated token
    public static function check($token){
        $tokenName = Config::get('session/token_name');
        
        //check if session exists with token defined in Config and if token supplied to check function equal session stored by user
        if(Session::exists($tokenName) && $token === Session::get($tokenName)){
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}



?>
