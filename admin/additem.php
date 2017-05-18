<?php

// include database class
include_once 'dbMysql.php';
$con = new database_con();

// insert code starts here
if(isset($_POST['addbtn']))
{
 $name = $_POST['name'];
 $value = $_POST['value'];
 $shape = $_POST['shape'];
 $color = $_POST['color'];
 $imagepath = $_POST['image_path'];
 
// call the insertitem method with the field values
 $res = $con->insertitem($name, $value, $shape, $color, $imagepath);
 if($res)
 {
  ?>
    <script>
        // alert if item added successfully
        alert('Item Added...');
        // redirect to treasure item page
        window.location = 'treasureitems.php'

    </script>
    <?php
 }
 else
 {
  ?>
        <script>
            // alert if item failed to add
            alert('error adding item...');
            // redirect to treasure item page
            window.location = 'treasureitems.php'

        </script>
        <?php
 }
}
// data insert code ends here.

?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta NAME="ROBOTS" CONTENT="INDEX, NOFOLLOW">
                <title>Altern8 Reality Admin Add Item Page</title>
                <link type="text/plain" rel="author" href="humans.txt" />
                <link rel="icon" href="../images/a8icon.gif" type="image/gif" sizes="16x16">
                <link href="https://fonts.googleapis.com/css?family=Raleway%7CUbuntu" rel="stylesheet">
                <link rel="stylesheet" href="../css/adminstyles.css">
            </head>

            <body>

                <div id="header">
                    <h1>ADD TREASURE ITEM</h1>
                </div>

                <div id="addItem">
                    <!-- form to input new item details -->
                    <form class="addForm" method="post">
                        <label for="name">Name</label>
                        <input type="text" name="name" placeholder="Item Name" required />

                        <label for="value">Value</label>
                        <input type="number" name="value" placeholder="Item Value" required />

                        <label for="shape">Shape</label>
                        <input type="text" name="shape" placeholder="Item Shape" required />

                        <label for="color">Colour</label>
                        <input type="text" name="color" placeholder="Item Colour" required />

                        <label for="image_path">Image Path</label>
                        <input type="text" name="image_path" placeholder="Item Image Path" required />

                        <button type="submit" name="addbtn" class="addBtn">Add Item</button>

                    </form>
                </div>

            </body>

            </html>
