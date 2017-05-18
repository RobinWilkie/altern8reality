<?php

// congiguration file to let us draw from any option we want

// create config class
    class Config{
        public static function get($path = null){ //static method passing the path
            if($path){
                $config = $GLOBALS['config']; //if path has been passed set new variable for the config
                $path = explode('/', $path); //explode by slash character and give an array back
                
                foreach($path as $bit){ //loops through each element of the config array
                    if(isset($config[$bit])){
                        $config = $config[$bit]; //set the config to the bit that we want
                    }
                } 
                
                return $config;
            }
            
            return false;
        }
    }

?>
