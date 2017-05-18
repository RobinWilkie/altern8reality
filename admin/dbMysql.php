<?php

// class connecting to database and containing function for user and items CRUD
class database_con {
    function __construct() {
        
        $conn = mysqli_connect("127.0.0.1", "root", "", "altern8reality");
        
        //check connection
        if (mysqli_connect_errno())
          {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
          }     
    }
    
    //function to return all user details
    public function selectusers() {
        
        $conn = mysqli_connect("127.0.0.1", "root", "", "altern8reality");
        $res = mysqli_query($conn, "SELECT * FROM users");
        return $res;
    }
    
    //function for adding new item
    public function insertuser($name, $email, $phone, $username, $password, $group, $score) {
      $conn = mysqli_connect("127.0.0.1", "root", "", "altern8reality");
      $res = mysqli_query($conn, "INSERT users(name,email,phone,username,password,group,score) VALUES('$name','$email','$phone','$username','$password','$group','$score')");
      return $res;
 }
    
    //function for deleting users
    public function deleteuser($table,$id) {
        
      $conn = mysqli_connect("127.0.0.1", "root", "", "altern8reality");
      $res = mysqli_query($conn, "DELETE FROM $table WHERE id=".$id);
      return $res;
     }
    
 
    //function for updating user details
    public function updateuser($table, $id, $name, $email, $phone) {
        
      $conn = mysqli_connect("127.0.0.1", "root", "", "altern8reality");
      $res = mysqli_query($conn,  "UPDATE $table SET name='$name', email='$email', phone='$phone' WHERE id=".$id);
      return $res;
     }
    
    //function to return all item details
    public function selectitem() {
        
        $conn = mysqli_connect("127.0.0.1", "root", "", "altern8reality");
        $res = mysqli_query($conn, "SELECT * FROM items");
        return $res;
    }
    
    //function for deleting item
    public function deletitem($table,$id) {
        
      $conn = mysqli_connect("127.0.0.1", "root", "", "altern8reality");
      $res = mysqli_query($conn, "DELETE FROM $table WHERE id=".$id);
      return $res;
     }
    
 
    //function for updating item details
    public function updateitem($table, $id, $name, $value, $shape, $color, $imagepath) {
        
      $conn = mysqli_connect("127.0.0.1", "root", "", "altern8reality");
      $res = mysqli_query($conn,  "UPDATE $table SET name='$name', value='$value', shape='$shape', color='$color', image_path='$imagepath' WHERE id=".$id);
      return $res;
     }
    
    //function for adding new item
    public function insertitem($name, $value, $shape, $color, $imagepath) {
      $conn = mysqli_connect("127.0.0.1", "root", "", "altern8reality");
      $res = mysqli_query($conn, "INSERT items(name,value,shape,color,image_path) VALUES('$name','$value','$shape','$color','$imagepath')");
      return $res;
 }
}


?>
