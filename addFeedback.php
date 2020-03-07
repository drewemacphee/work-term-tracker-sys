<?php
session_start();
include_once("dbinfo.php");

    //establish connection
    $conn = mysqli_connect($db_host, $db_user, $db_password, "work_term_employer_tracker");
    //check for successful connection

    if(!$conn) {
        die("Connection failed:" . mysqli_connect_error());
    }
   

   //grab variables and make sure all fields are filled in and clean string
   $feedback_rating = "";
   $comments = "";
   $num_of_terms = "";
   $employer_id = $_SESSION['employer_id'];

   //get the company name. could throw this into a session var from the previous screen i guess
   $sql = "SELECT company_name FROM employer WHERE employer_id='$employer_id' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $company_name = $row["company_name"];
        }


//if method = POST or GET (whatever im using), then execute php
$insertNew = false;
$errors = false;
if ( isset( $_POST["feedbackForm"] ) ) { 
    //retrieve variables and clean string
    $feedback_rating = $_POST["feedback_rating"];
    $comments = $_POST["comments"];
    
    $feedback_rating = mysqli_real_escape_string($conn, $feedback_rating);
    $comments = mysqli_real_escape_string($conn, $comments);
    
    //check that none are empty?
    if ( $feedback_rating == null || empty($feedback_rating)
        || $comments == null || empty($comments) ) { 
        $errors = true; 

    } else {
        //insert variables into database
        $sql = "INSERT INTO feedback (feedback_rating, comments, employer_id) VALUES ('$feedback_rating', '$comments', '$employer_id')";
        $result = mysqli_query($conn, $sql);

        //pull all of the currents ratings from feedback plus this one and divide by work terms
        $total_rating = 0;
        $count = 0;
        $sql = "SELECT feedback_rating FROM feedback WHERE employer_id='$employer_id'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $total_rating += $row["feedback_rating"];
            $count++;
        }

        $employer_rating = $total_rating/$count;
     
        
        //insert new rating into the database
        $sql = "UPDATE employer SET rating='$employer_rating' WHERE employer_id='$employer_id'";
        $result = mysqli_query($conn, $sql);

        //print out success message at top of blank form
        $insertNew = true;
    }
}

?>

<!DOCTYPE html>
<HTML>
    <head>
    <link rel="stylesheet" href="./style2.css">
	
        <title>
            Add new student feedback
        </title>
    </head>
    <div class="logo-header">
    <div class="header-row">	
        <div class="grid1">	
            <a href="/WorkTermTracker/mainMenu.php">	
                <img src="images/nscc-logo.png" class="nscc-logo" alt="logo">	
            </a>	
        </div>	
        <div class="grid2">	
            <h2>Work Term Employer Tracker<br><br>Add New Student Feedback</h2>	
        </div>	
    </div>
    </div>

    <body>

        <div class="update-page">
        <br>

            <div class="message">
        <!-- Success message here, put form back to empty. need styling for success msg-->
        <?php if ($insertNew == true) {echo "Success! You have entered a new feedback!";
            $feedback_rating = "";
            $comments = "";}
            //ERROR message here if something is missing in the form (all fields required) ** need styling
        elseif ($errors == true) {echo "Error! You are missing 1 or more fields. Please check your form again.";}  ?>
            </div>


            <div class="form">
                <div class="welcome">

        <form method="POST">
        <!-- need styling for entry form-->
            Company: <?php echo $company_name;?><br /><br />
            Rating out of 10: <input type="number" name="feedback_rating" min="1" max="10" value="<?php echo $feedback_rating; ?>"><br />
            Comments:<br><textarea name="comments" rows="10" cols="60" placeholder="Tell us about your work term experience..." required ><?php echo $comments; ?></textarea><br />
            
            
            <input type="submit" name="feedbackForm" value="Add Feedback" class="submitBtn" /><br />

            </div>
        </div>
    </div>

        </form>
    </body>
</HTML>