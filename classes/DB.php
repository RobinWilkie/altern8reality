<?php

// class for accessing database
// database wrapper using PDO that can be used in any project

// define database class with private properties not available outside this class
class DB{
    // private properties only used within this class
    private static $_instance = null; // property stores an instance of the database
    private $_pdo, // stores pdo object so we can use it elsewhere
            $_query, // last query that is executed
            $_error = false, // checks if error or not
            $_results, // stores result set
            $_count = 0; // counts the results
    
    // constructor function to connect to the database with pdo by getting the Config class details
    private function __construct(){
        try{
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
        } catch(PDOException $e){
            die($e->getMessage());
        }
    }
    
    // create a new DB instance so we don't have to keep returning to database to reconnect every time
    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    
    // method for queries on the database
    public function query($sql, $params = array()) { // empty array so we can check if anything has been defined
        $this->_error = false; // always set to false so we're not returning error
        if($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if(count($params)) {
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param); // binds value in pdo
                    $x++;
                }
            }
            
            if($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowcount();
            } else {
                $this->_error = true;
            }
        }
        
        return $this;
    }
    
    //create an action function to perform different tasks
    public function action($action, $table, $where = array()){
        if(count($where) === 3){
            $operators = array('=', '>', '<', '>=', '<='); // define the operators we can use
            
            // extract elements from the 'where' array
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
            
            // check wether operator is in array
            if(in_array($operator, $operators)){
                // construct query using variables for the action, table, field and operator. The value is a question mark as the query binds the value on
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                
                // use the query method and if no error on the query return current object
                if(!$this->query($sql, array($value))->error()){
                    return $this;
                }
            }
        }
        
        return false;
    }
    
    // 'get' method to retrieve results from table
    public function get($table, $where){
        return $this->action('SELECT *', $table, $where);
    }
    
    // pass delete function to the action
    public function delete($table, $where){
        return $this->action('DELETE', $table, $where);
    }
    
    // function to insert data
    public function insert($table, $fields = array()){
        if(count($fields)){
            $keys = array_keys($fields);
            $values = '';
            $z = 1;
            
            // set values as question marks and add commas between until values end
            foreach($fields as $field){
                $values .= '?';
                if($z < count($fields)){
                    $values .= ', ';
                }
                $z++;
            }
            
            // insert user details into table
            $sql = "INSERT INTO {$table} (`" .implode('`,`', $keys) . "`) VALUES ({$values})";
            
            if($this->query($sql, $fields)->error()){
                return true;
            }
        }
        return false;
    }
    
    //function to update user details
    public function update($table, $id, $fields){
        $set = '';
        $y = 1;
        
        foreach($fields as $name => $value){
            $set .= "{$name} = ?";
            if($y < count($fields)){
                $set .= ', ';
            }
            $y++;
        }
        
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
        
       if(!$this->query($sql, $fields)->error()){
                return true;
        }
        return false;
    }
    
    //function to display results
    public function results(){
        return $this->_results;
    }
    
    //function to find first result in a table
    public function first(){
        return $this->results()[0];
    }
    
    //function to display error
    public function error(){
        return $this->_error;
    }
    
    // count method
    public function count(){
        return $this->_count;
    }
}


?>
