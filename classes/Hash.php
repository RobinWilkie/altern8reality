<?php

    // make salt for improved security on password hash
    class Hash{
        public static function make($string, $salt = ''){
            return hash('sha256', $string . $salt); //adds salt to hashed password. The SHA-256 algorithm generated here is an almost-unique, fixed size 256-bit hash. Hash is a one way function as it cannot be decrypted back
        }
        
        public static function salt($length){
            return mcrypt_create_iv($length); // generate salt of particular length. mcrypt_create_iv creates initialization vector from a random source
        }
        
        public static function unique(){
            return self::make(uniqid()); // makes hash
        }
    }


?>
