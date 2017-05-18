<?php

include_once('dbMysql.php');
$con = new database_con();
$table = "users";
$conn = mysqli_connect("127.0.0.1", "root", "", "altern8reality");

// data insert code starts here.
if(isset($_GET['edit_id'])) {
 $sql = "SELECT * FROM users WHERE id=".$_GET['edit_id'];
 $result = mysqli_query($conn, $sql);
 $rows = mysqli_fetch_array($result);
}

// data update code starts here.
if(isset($_POST['btn-update'])) {
 $name = $_POST['name'];
 $email = $_POST['email'];
 $phone = $_POST['phone'];
 
 $id = $_GET['edit_id'];
 $res = $con->updateuser($table,$id,$name,$email,$phone);
    
 if($res) {
  ?>
    <script>
        alert('Record updated...');
        window.location = 'admin.php'

    </script>
    <?php
 }
 else {
  ?>
        <script>
            alert('error updating user...');
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
                <title>Altern8 Reality Admin Edit Users Page</title>
                <link type="text/plain" rel="author" href="humans.txt" />
                <link rel="icon" href="../images/a8icon.gif" type="image/gif" sizes="16x16">
                <link href="https://fonts.googleapis.com/css?family=Raleway%7CUbuntu" rel="stylesheet">
                <link rel="stylesheet" href="../css/adminstyles.css">
            </head>

            <body>
                <div id="userEdit">
                    <h1>USER</h1>
                    <form method="post">
                        <table>
                            <tr>
                                <td>Name</td>
                                <td><input type="text" name="name" placeholder="Name" value="<?php echo $rows['name']; ?>" /></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td><input type="text" name="email" placeholder="Email" value="<?php echo $rows['email']; ?>" /></td>
                            </tr>
                            <tr>
                                <td>Phone Number</td>
                                <td><input type="text" name="phone" placeholder="Phone" value="<?php echo $rows['phone']; ?>" /></td>
                            </tr>
                        </table>
                        <button type="submit" name="btn-update" class="updateBtn">UPDATE DETAILS</button>
                    </form>
                </div>
            </body>

            </html>
