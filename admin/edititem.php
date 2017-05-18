<?php

include_once('dbMysql.php');
$con = new database_con();
$table = "items";
$conn = mysqli_connect("127.0.0.1", "root", "", "altern8reality");

// data insert code starts here.
if(isset($_GET['edit_id'])) {
 $sql = "SELECT * FROM items WHERE id=".$_GET['edit_id'];
 $result = mysqli_query($conn, $sql);
 $rows = mysqli_fetch_array($result);
}

// data update code starts here.
if(isset($_POST['btn-update'])) {
 $name = $_POST['name'];
 $value = $_POST['value'];
 $shape = $_POST['shape'];
 $color = $_POST['color'];
 $imagepath = $_POST['image_path'];
 
 $id = $_GET['edit_id'];
 $res = $con->updateitem($table,$id,$name, $value, $shape, $color, $imagepath);
    
 if($res) {
  ?>
    <script>
        alert('Item updated...');
        window.location = 'admin.php'

    </script>
    <?php
 }
 else {
  ?>
        <script>
            alert('error updating item...');
            window.location = 'admin.php'

        </script>
        <?php
 }
}
// data update code ends here.

?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta NAME="ROBOTS" CONTENT="INDEX, NOFOLLOW">
                <title>Altern8 Reality Admin Edit Items Page</title>
                <link type="text/plain" rel="author" href="humans.txt" />
                <link rel="icon" href="../images/a8icon.gif" type="image/gif" sizes="16x16">
                <link href="https://fonts.googleapis.com/css?family=Raleway%7CUbuntu" rel="stylesheet">
                <link rel="stylesheet" href="../css/adminstyles.css">
            </head>

            <body>
                <div id="userEdit">
                    <h1>TREASURE ITEMS</h1>
                    <form method="post">
                        <table>
                            <tr>
                                <td>Name</td>
                                <td><input type="text" name="name" placeholder="Name" value="<?php echo $rows['name']; ?>" /></td>
                            </tr>
                            <tr>
                                <td>Shape</td>
                                <td><input type="text" name="shape" placeholder="Shape" value="<?php echo $rows['shape']; ?>" /></td>
                            </tr>
                            <tr>
                                <td>Value</td>
                                <td><input type="number" name="value" placeholder="Value" value="<?php echo $rows['value']; ?>" /></td>
                            </tr>
                            <tr>
                                <td>Colour</td>
                                <td><input type="text" name="color" placeholder="Colour" value="<?php echo $rows['color']; ?>" /></td>
                            </tr>
                            <tr>
                                <td>Image Path</td>
                                <td><input type="text" name="imagepath" placeholder="Image Path" value="<?php echo $rows['image_path']; ?>" /></td>
                            </tr>
                        </table>
                        <button type="submit" name="btn-update" class="updateBtn">UPDATE ITEM</button>
                    </form>
                </div>
            </body>

            </html>
