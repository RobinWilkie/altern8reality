<?php

//Validation
//validation to work across all pages including register and user changing profile

//create a Validate class to run though all possible errors
class Validate {
    private $_passed = false,
            $_errors = array(),
            $_db = null;
    
    public function __construct(){
        $this->_db = DB::getInstance();
    }
    
    //function to check if field inputs are within our parameters and, if not, give an error message
    public function check($source, $items = array()){
        foreach($items as $item => $rules){
            foreach($rules as $rule => $rule_value){
                
                $value = trim($source[$item]);
                $item = escape($item);
                
                if($rule === 'required' && empty($value)){
                    $this->addError("{$item} is required");
                } else if(!empty($value)){
                    switch($rule){
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->addError("{$item} must be a minimum of {$rule_value} characters");
                            }
                        break;
                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->addError("{$item} must be a maximum of {$rule_value} characters");
                            }
                        break;
                        case 'matches':
                            if($value != $source[$rule_value]){
                                $this->addError("{$rule_value} must match {$item}");
                            }
                        break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count()){
                                $this->addError("{$item} already exists");
                            }
                        break;
                    }
                }
            }
        }
        
        if(empty($this->_errors)){
            $this->_passed = true;
        }
        return $this;
    }
    
    
    private function addError($error){
        $this->_errors[] = $error;
    }
    
    
    //return a list of errors
    public function errors(){
        return $this->_errors;
    }
    
    
    //check if input has passed
    public function passed(){
        return $this->_passed;
    }
}


?>
