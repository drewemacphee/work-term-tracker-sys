<?php
session_start();
include_once("dbinfo.php");


    //establish connection
    $conn = mysqli_connect($db_host, $db_user, $db_password, "work_term_employer_tracker");
    //check for successful connection

    if(!$conn) {
        die("Connection failed:" . mysqli_connect_error());
    }
    //working
   //echo "Connection new";

   $companyFound == false;

   //put this in a if isset for if they clicked search id
if ( isset( $_POST["findID"] ) ) { 
    //retrieve variables and clean string
    $employer_id = $_POST["employer_id"];
    $employer_id = mysqli_real_escape_string($conn, $employer_id);
    $_SESSION['employer_id'] = $employer_id;

    //run sql select query to grab all ratings and comments from table and put into array
    $sql = "SELECT feedback_rating, comments FROM feedback WHERE employer_id='$employer_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)>0) {
        
        while($row = mysqli_fetch_assoc($result)){
          $rating = $row['feedback_rating'];
          $comment = $row['comments'];
        
          $feedback_array = [];
        
          array_push($feedback_array, $rating, $comment);
         
          $query_records[] = $feedback_array;
            
        }
        $_SESSION['feedback'] = $query_records;
 
        //print out success message at top of blank form
        $companyFound = true;
    }

    $name_query = "SELECT company_name FROM employer WHERE employer_id='$employer_id'";
    $name_result = mysqli_query($conn, $name_query);

    if (mysqli_num_rows($name_result)>0) {
        
      while($row = mysqli_fetch_assoc($name_result)){
        $company_name = $row['company_name'];
     
        $_SESSION['company_feedback_name'] = $company_name;
          
      }
    }
}
   
//if method = POST or GET (whatever im using), then execute php

$errors = false;
if ( isset( $_POST["generateFeedbackReport"] ) ) { 
  //create new title string
  $_SESSION['reportTitle']='FEEDBACK REPORT';
  header('Content-Type: application/pdf');
  header('Location: generateReport.php');
   
}

?>

<!DOCTYPE html>
<HTML>
    <head>
        <title>
            Update employer
        </title>
        <link rel="stylesheet" href="./style2.css">
    </head>
    <body>
    <div class="logo-header">
    <div class="header-row">	
        <div class="grid1">	
            <a href="/WorkTermTracker/mainMenu.php">	
                <img src="images/nscc-logo.png" class="nscc-logo" alt="logo">	
            </a>	
        </div>	
        <div class="grid2">	
            <h2>Work Term Employer Tracker</h2>	
        </div>	
    </div>
    </div>
        
        <!-- ask for user to enter company id they want to update-->
        <div class="update-page">
            <div class="header">
        <h2>
            <a href="mainmenu.php">
                Generate Student Feedback
            </a>
        </h2>
            </div>
            <div class="form">
                <div class="welcome">
        <form method="POST">
            Please enter company ID you wish to generate a Student Feedback Report for:<br>
            <input type="text" name="employer_id" value="<?php echo $employer_id; ?>" required /><br />
            <input type="submit" name="findID" value="Find Employer" class="submitBtn" /><br />
        </form>
        
        <?php
        if(isset( $_POST["findID"] )) {
            if ($companyFound == true)
            //success message for finding the ID. needs to be styled.
                {echo "Success! You have selected " . $_SESSION['company_feedback_name'] . ". If this is the correct employer, proceed with report. ";?><br><br>
            <!-- if the id has been found successfully, show this-->
                
            <!-- welcome message closing div -->
        </div>

            <form method="POST" target="_blank">

                    <div class="grid-item3">

                    <div class="submit">
                <input type="submit" name="generateFeedbackReport" value="Generate Feedback" class="submitBtn" />
                </div>

                    </div>
                </div>

                    <!-- update-page closing div -->
                </div>
            </form>

            
        </body>
        </HTML><?php 
        }
        //didn't find the company id in the database. needs to be styled.
        elseif($companyFound == false) {echo "Error. The ID you have entered does not exist.";} 
        } 
        //this is a success message for updating the information in the database. should be styled.
        if ($updateSuccessful == true) {echo "Successfully updated employer entry.";}?>
        
        