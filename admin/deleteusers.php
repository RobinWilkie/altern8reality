<?php

include_once('dbMysql.php');
$con = new database_con();
$table = "users";

if(isset($_GET['delete_id'])) {
 $id=$_GET['delete_id'];
 $res=$con->deleteuser($table,$id);
    
 if($res) {
  ?>
    <script>
        alert('User Deleted ...')
        window.location = 'users.php'

    </script>
    <?php
 }
 else {
  ?>
        <script>
            alert('User not Deleted !!!')
            window.location = 'users.php'

        </script>
        <?php
 }
}

?>
