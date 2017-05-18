<?php

// include database class
include_once 'dbMysql.php';
$con = new database_con();

// insert code starts here
if(isset($_POST['addbtn']))
{
 $name = $_POST['name'];
 $email = $_POST['email'];
 $phone = $_POST['phone'];
 $username = $_POST['username'];
 $password = $_POST['password'];
 $group = $_POST['group'];
 $score = $_POST['score'];
 
    // call the insertuser method with the new field values
 $res = $con->insertuser($name, $email, $phone, $username, $password, $group, $score);
 if($res)
 {
  ?>
    <script>
        // alert if item added successfully
        alert('User Added...');
        // redirect to treasure item page
        window.location = 'users.php'

    </script>
    <?php
 }
 else
 {
  ?>
        <script>
            // alert if item failed to add
            alert('error adding user...');
            // redirect to treasure item page
            window.location = 'users.php'

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
                <title>Altern8 Reality Admin Add User Page</title>
                <link type="text/plain" rel="author" href="humans.txt" />
                <link rel="icon" href="../images/a8icon.gif" type="image/gif" sizes="16x16">
                <link href="https://fonts.googleapis.com/css?family=Raleway%7CUbuntu" rel="stylesheet">
                <link rel="stylesheet" href="../css/adminstyles.css">
            </head>

            <body>

                <div id="header">
                    <h1>ADD NEW USER</h1>
                </div>

                <div id="addUser">
                    <!-- form to input new user details -->
                    <form class="addForm" method="post">
                        <label for="name">Name</label>
                        <input type="text" name="name" placeholder="Name" required />

                        <label for="email">Email</label>
                        <input type="text" name="email" placeholder="Email" required />

                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" placeholder="Phone Number" required />

                        <label for="username">Username</label>
                        <input type="text" name="username" placeholder="Username" required />

                        <label for="password">Password</label>
                        <input type="password" name="password" placeholder="Password" required />

                        <label for="Group">Group</label>
                        <input type="number" name="group" placeholder="Group (1:user 2:admin)" required />

                        <label for="score">Score</label>
                        <input type="number" name="score" placeholder="Score" required />

                        <button type="submit" name="addbtn" class="addBtn">Add New User</button>
                    </form>
                </div>

            </body>

            </html>
