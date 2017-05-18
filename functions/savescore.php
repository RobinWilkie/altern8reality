<?php

 // include the initialisation file
    require_once 'core/init.php';


    if(Session::exists('home')){
        echo '<p>' . Session::flash('home') . '</p>';
    }

    $user = new User();

    $conn = new mysqli("127.0.0.1", "root", "", "altern8reality"); // Create connection

    // if score has been submitted
    if(isset($_POST['submit'])){

    $data = $_POST['userscore'];

    // update user score in database
    $query = mysqli_prepare($conn , "UPDATE users SET score=? WHERE username=?");
        mysqli_stmt_bind_param($query, $score, $user);
        mysqli_stmt_execute($query);


mysqli_stmt_close($query);

mysqli_close($con);


    }
?>
