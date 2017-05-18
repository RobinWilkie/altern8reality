<?php

    // create class for user

    class User{
        private $_db,
                $_data,
                $_sessionName,
                $_cookieName,
                $_isLoggedIn;
        
        // constructor
        public function __construct($user = null){
            $this->_db = DB::getInstance();
            
            $this->_sessionName = Config::get('session/session_name');
            $this->_cookieName = Config::get('remember/cookie_name');
            
            if(!$user){
                if(Session::exists($this->_sessionName)){
                    $user = Session::get($this->_sessionName);
                    
                    if($this->find($user)){
                        $this->_isLoggedIn = true;
                    }
                        else{
                        //process logout
                     }                
                }
            } else{
                $this->find($user);
            }
        }
        
        // method for updating user details
        public function update($fields = array(), $id = null){
            
            //if id is defined and user is logged in
            if(!$id && $this->isLoggedIn()){
                $id = $this->data()->id; //update this user's id
            }
            
            
            // call update method on database object
            if(!$this->_db->update('users', $id, $fields)){
                throw new Exception('There was a problem updating');
            }
        }
        
        // method for inserting new user into database
        public function create($fields = array()){
            if($this->_db->insert('users', $fields)){
                throw new Exception('There was a problem creating an account');
            }
        }
        
        // method for finding users in database
        public function find($user = null){
            if($user){
                $field = (is_numeric($user)) ? 'id' : 'username';
                $data = $this->_db->get('users', array($field, '=', $user));
                
                if($data->count()){
                    $this->_data = $data->first();
                    return true;
                }
            }
            return false;
        }
        
        // method for logging in user
        public function login($username = null, $password = null, $remember = false){ // pass in username and password, null by default
            
            //check if user already exists
            if(!$username && !$password && $this->exists()){
                Session::put($this->_sessionName, $this->data()->id);
            } 
            // otherwise log user in
            else{
                $user = $this->find($username);
           
                if($user){
                   if($this->data()->password === Hash::make($password, $this->data()->salt)){
                       Session::put($this->_sessionName, $this->data()->id);

                       // if 'remember me' is activated
                       if($remember){
                           $hash = Hash::unique(); // generate an unique hash
                           $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));// check if hash is stored in database table 'user_session'

                           // if hash check returns zero
                               if(!$hashCheck->count()){ 
                                   $this->_db->insert('users_session', array(
                                        'user_id' => $this->data()->id,
                                        'hash' => $hash
                                  )); // insert record into database
                               } else{
                                   $hash = $hashCheck->first()->hash; //otherwise set hash to database record that already exists
                               }

                           // store cookie with expiry time that is set in Config file
                           Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                       }
                       return true;
                   } 
                }
            }
            return false;
            
        }
        
        // function to check if a user has administration permissions
        public function hasPermission($key){
            $group = $this->_db->get('groups', array('id', '=', $this->data()->group));
            // check if user is in a group
            if($group->count()){
                // decode json in groups table for admin
                $permissions = json_decode($group->first()->permissions, true);
                if($permissions[$key] == true){
                    return true;
                }
            }
            return false;
        }
        
        // function to check if an istance exists
        public function exists(){
            return (!empty($this->_data)) ? true : false;
        }    
        
        //logout function that deletes session and cookie
        public function logout(){
            $this->_db->delete('users_session', array('user_id', '=', $this->data()->id));
            
            Session::delete($this->_sessionName);
            Cookie::delete($this->_cookieName);
        }
        
        public function data(){
            return $this->_data;
        }
        
        // function to check if a user is logged in
        public function isLoggedIn(){
            return $this->_isLoggedIn;
        }
    }



?>
