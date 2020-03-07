<?php 
    include_once("dbinfo.php");

    //establish connection
    $conn = mysqli_connect($db_host, $db_user, $db_password, "work_term_employer_tracker");
    //check for successful connection

    if(!$conn) {
        die("Connection failed:" . mysqli_connect_error());
    }
    //working
   //echo "Connection AUTH";
//if method = POST or GET (whatever im using), then execute php
$error = 0;
if ( isset( $_POST["loginForm"] ) ) { 
    //retrieve username and password
    $username = $_POST["username"];
    $password = $_POST["password"];

    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
   
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    //check if it exists in the table by using num_rows function
    //if you get 1 row, thats 1 exact match which means it exists in database
    if (mysqli_num_rows($result)==1) {
    //redirect to index/menu if login successful
      //For Mac
       header("Location: http://localhost:8888/WorkTermTracker/mainMenu.php");
      //For Windows
      // header("Location: http://localhost/WorkTermTracker/mainMenu.php");

    } else {
    //if it does not exist, redirect to login form with erro
        $error = 1;
    }
}

?>

<!DOCTYPE html>
<html lang="en" >
  <head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./style.css">
  </head>

  <body>
    <div class="login-page">
      <div class="form">
        <form class="login-form" method="POST">
        <img src="images/bannerlogo.png" alt="bannerlogo" class="logo">
          <div class="blank-space-7"></div>
          <h3 class="title">Work Term Tracker</h3>
          <h3 class="title">Faculty Login Terminal</h3>
          </br>
          <img src="images/icon.png" alt="DBIcon" class="icon">
          <div class="blank-space-1"></div>

          <div class="login-label">Username</b></div>
          <input type="text" name="username" value="" required />
          <!-- <div class="blank-space-7"></div> -->

          <div class="login-label">Password</b></div>
          <input type="password" name="password" value="" required />      
          <div class="blank-space-1"></div>

          <input type="submit" name="loginForm" value="Login >" class="submitButton" /><br />
          <div class="blank-space-7"></div>

          <?php if ($error == 1) {echo "Sorry, that username or password does not match our records.";}  ?>

        </form>
      </div>
    </div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script><script  src="./script.js"></script>

  </body>
</html>