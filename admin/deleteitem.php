<?php

include_once('dbMysql.php');
$con = new database_con();
$table = "items";

if(isset($_GET['delete_id'])) {
 $id=$_GET['delete_id'];
 $res=$con->deletitem($table,$id);
    
 if($res) {
  ?>
    <script>
        alert('Item Deleted ...')
        window.location = 'treasureitems.php'

    </script>
    <?php
 }
 else {
  ?>
        <script>
            alert('Item not Deleted !!!')
            window.location = 'treasureitems.php'

        </script>
        <?php
 }
}

?>
