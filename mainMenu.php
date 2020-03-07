<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once("dbinfo.php");

session_start();
//establish connection
$conn = mysqli_connect($db_host, $db_user, $db_password, "work_term_employer_tracker");
//check for successful connection

if(!$conn) {
    die("Connection failed:" . mysqli_connect_error());
}
//working
//echo "Connection index";

//database stuff
$jobType = "";
$province = "";
$keySkills = "";
$result = NULL;
$searchResults = false;

//if method = POST or GET (whatever im using), then execute php
if ( isset( $_POST["searchMenu"] ) ) { 
    
    //grab search information and put into variables
    $jobType = $_POST["job_type"];
    $province = $_POST["province"];
    $keySkills = $_POST["key_skills"];
    
    $jobType = mysqli_real_escape_string($conn, $jobType);
    $province = mysqli_real_escape_string($conn, $province);
    $keySkills = mysqli_real_escape_string($conn, $keySkills);

    $selectJob = true;
    $selectProvince = true;
    $selectSkills = true;
    
    //two will have to check if default "all" was selected - that counts as no filter
    //in this example just check if empty field, know not to use as limit in query. will use for SKILLS
    if ( $jobType == "All" ) {
        $selectJob = false;
    }
    if ( $province == "All" ) {
        $selectProvince = false;
    }
    if ( $keySkills == null || empty($keySkills) ) {
        $selectSkills = false;
    }
    //have 3 different quieries for if 1, 2 or 3 pieces of info was searched to create proper WHERE clause
    if(($selectJob == false && $selectProvince == false) && $selectSkills == false) {
        //search with 0 limiting factors - query
        $sql = "SELECT * FROM employer";
        $result = mysqli_query($conn, $sql);
    } elseif($selectJob == false && $selectProvince == false){
        //search keyskills for string match
        $sql = "SELECT * FROM employer WHERE skills LIKE '%$keySkills%'";
        $result = mysqli_query($conn, $sql);
    } elseif($selectJob == false && $selectSkills == false) {
        //search province
        $sql = "SELECT * FROM employer WHERE province = '$province'";
        $result = mysqli_query($conn, $sql);
    } elseif($selectSkills == false && $selectProvince == false) {
        //search job type
        $sql = "SELECT * FROM employer WHERE category = '$jobType'";
        $result = mysqli_query($conn, $sql);
    } elseif($selectJob == false) {
        //search province and keyskills
        $sql = "SELECT * FROM employer WHERE province = '$province' AND skills LIKE '%$keySkills%'";
        $result = mysqli_query($conn, $sql);
    } elseif($keySkills == false) {
        //search province and job type
        $sql = "SELECT * FROM employer WHERE province = '$province' AND category = '$jobType'";
        $result = mysqli_query($conn, $sql);
    } elseif($province == false) {
        //search key skills and job type
        $sql = "SELECT * FROM employer WHERE category = '$jobType' AND skills LIKE '%$keySkills%'";
        $result = mysqli_query($conn, $sql);
    } 


    if (mysqli_num_rows($result)>0) {
       
        //put query results in a variable and display below the search bar
        while($row = mysqli_fetch_assoc($result)){

        $employer_id = $row['employer_id'];
        $company_name = $row['company_name'];
        $job_type = $row['category'];
        $contact_name = $row['contact_name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $street = $row['street'];
        $city = $row['city'];
        $province = $row['province'];
        $postal_code = $row['postal_code'];
        $key_skills = $row['skills'];
        $rating = $row['rating'];
        $num_of_terms = $row['num_of_terms'];
        
        // Take whataver is in the current row and store it  
        // $employer_array that will hold all records of a current request
        
        $employer_array = [];
        
        array_push($employer_array, $employer_id, $company_name, $job_type, $contact_name, $email, $phone, $street, $city, $province, $postal_code, $key_skills, $rating, $num_of_terms);
        
        $query_records[] = $employer_array;

        $searchResults = true;
        }
    
    $_SESSION['data']=$query_records;

    }
    else {
        echo "No results found";
        $searchResults = false;
        unset($_SESSION['data']);
    }
}

if (isset( $_POST["masterReport"])) {

  $_SESSION['reportTitle']='MASTER REPORT';
  header('Location: generateReport.php');
  header('Content-Type: application/pdf');

} elseif (isset( $_POST["detailedReport"])) {

  $_SESSION['reportTitle']='DETAILED REPORT';
  header('Content-Type: application/pdf');
  header('Location: generateReport.php');

} elseif (isset($_POST["feedbackReport"])) {

  $_SESSION['reportTitle']='FEEDBACK REPORT';

}

//html displaying the menu
?>
<!DOCTYPE html>
<HTML>
<head>
<link rel="stylesheet" href="./menu-style.css">
</head>
    <body>
        <!-- search menu here -->
        
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
        <div class="row-topper">	
            <h3>Search Employer:</h3>	
        </div>
        <div class="row-top">

            <form method="POST">
            <!--<img src="images/icon.png" alt="DBIcon" class="icon">-->
                Job Type:<div class="blank-space-half"></div><select name="job_type" class="jobType" >
                    <option value="All">All</option>
                    <option value="Building and Manufacturing"
                    <?php if ($jobType != "All" && $jobType=="Building and Manufacturing") echo " selected "; ?>
                    >Building and Manufacturing</option>
                    <option value="Business"
                    <?php if ($jobType != "All" && $jobType=="Business") echo " selected "; ?>
                    >Business</option>
                    <option value="Creative and Digital Media"
                    <?php if ($jobType != "All" && $jobType=="Creative and Digital Media") echo " selected "; ?>
                    >Creative and Digital Media</option>
                    <option value="Culinary"
                    <?php if ($jobType != "All" && $jobType=="Culinary") echo " selected "; ?>
                    >Culinary</option>
                    <option value="Tourism"
                    <?php if ($jobType != "All" && $jobType=="Tourism") echo " selected "; ?>
                    >Tourism</option>
                    <option value="Engineering Technologies"
                    <?php if ($jobType != "All" && $jobType=="Engineering Technologies") echo " selected "; ?>
                    >Engineering Technologies</option>
                    <option value="Environment, Sustainability and Natural Resources"
                    <?php if ($jobType != "All" && $jobType=="Environment, Sustainability and Natural Resources") echo " selected "; ?>
                    >Environment, Sustainability and Natural Resources</option>
                    <option value="Health and Wellness"
                    <?php if ($jobType != "All" && $jobType=="Health and Wellness") echo " selected "; ?>
                    >Health and Wellness</option>
                    <option value="IT and Data Analytics"
                    <?php if ($jobType != "All" && $jobType=="IT and Data Analytics") echo " selected "; ?>
                    >IT and Data Analytics</option>
                    <option value="Language and Cultural Studies"
                    <?php if ($jobType != "All" && $jobType=="Language and Cultural Studies") echo " selected "; ?>
                    >Language and Cultural Studies</option>
                    <option value="Marine, Fisheries and Oceans"
                    <?php if ($jobType != "All" && $jobType=="Marine, Fisheries and Oceans") echo " selected "; ?>
                    >Marine, Fisheries and Oceans</option>
                    <option value="Social and Community Supports"
                    <?php if ($jobType != "All" && $jobType=="Social and Community Supports") echo " selected "; ?>
                    >Social and Community Supports</option>
                    <option value="Surveying, Mapping and Geomatics"
                    <?php if ($jobType != "All" && $jobType=="Surveying, Mapping and Geomatics") echo " selected "; ?>
                    >Surveying, Mapping and Geomatics</option>
                    <option value="Transportation"
                    <?php if ($jobType != "All" && $jobType=="Transportation") echo " selected "; ?>
                    >Transportation</option>
                    </select> <div class="blank-space-3"></div>
                Province:<div class="blank-space-half"></div> <select name="province">
                    <option value="All">All</option>
                    <option value="AB"
                    <?php if ($province != "All" && $province=="AB") echo " selected "; ?>
                    >Alberta</option>
                    <option value="BC"
                    <?php if ($province != "All" && $province=="BC") echo " selected "; ?>
                    >British Columbia</option>
                    <option value="MB"
                    <?php if ($province != "All" && $province=="MB") echo " selected "; ?>
                    >Manitoba</option>
                    <option value="NB"
                    <?php if ($province != "All" && $province=="NB") echo " selected "; ?>
                    >New Brunswick</option>
                    <option value="NL"
                    <?php if ($province != "All" && $province=="NL") echo " selected "; ?>
                    >Newfoundland and Labrador</option>
                    <option value="NS"
                    <?php if ($province != "All" && $province=="NS") echo " selected "; ?>
                    >Nova Scotia</option>
                    <option value="ON"
                    <?php if ($province != "All" && $province=="ON") echo " selected "; ?>
                    >Ontario</option>
                    <option value="PE"
                    <?php if ($province != "All" && $province=="PE") echo " selected "; ?>
                    >Prince Edward Island</option>
                    <option value="QC"
                    <?php if ($province != "All" && $province=="QC") echo " selected "; ?>
                    >Quebec</option>
                    <option value="SK"
                    <?php if ($province != "All" && $province=="SK") echo " selected "; ?>
                    >Saskatchewan</option>
                    <option value="NT"
                    <?php if ($province != "All" && $province=="NT") echo " selected "; ?>
                    >Northwest Territories</option>
                    <option value="NU"
                    <?php if ($province != "All" && $province=="NU") echo " selected "; ?>
                    >Nunavut</option>
                    <option value="YT"
                    <?php if ($province != "All" && $province=="YT") echo " selected "; ?>
                    >Yukon</option>
                </select> <div class="blank-space-3"></div>
                Key Skills: <div class="blank-space-half"></div><input type="text" name="key_skills" class="searchBar" placeholder="i.e.; leadership" />
                <div class="blank-space-1"></div>
                <div class="submitBtn-outer">
                <input type="submit" name="searchMenu" value="Search" class="submitBtn" />
            </div>
            </form>
        </div>
        <div class="row-bottom">
            <!-- section for buttons here -->
          <div class="idontknow">
            <form action="addNew.php">
            <div class="addBtn2">
                <button type="submit" class="addBtn">Add New Employer</button>
            </div>
            </form>
            <form action="update.php">
                <button type="submit" class="updateBtn">Update Employer</button>
            </form>
            </div>
            <!-- section for generating reports here-->
            <div class="row-bottom-inner">
                Generate reports: 
                <form method="POST" target="_blank"  >
                    <button type="submit" name="masterReport" >Master</button>
                </form>
                <form method="POST" target="_blank" >
                    <button type="submit" name="detailedReport" <?php if(!$searchResults) {echo "disabled";} ?>>Detailed</button>
                </form>
                <form action="selectFeedbackReport.php" >
                    <button type="submit" name="feedbackReport" >Student Feedback</button>
                </form>
              
        
            </div>
        </div>
      


<?php
//display results here and format into html
if ($searchResults) { //if the query yields a result
  // Get the row
  foreach ($_SESSION['data'] as $row) {
    //now here you will make the html for your card (just one)
    //for each row it will generate a card inside this loop.
    ?>
        <div class="outsideCC">  
            <div class="cardContainer">
                <h1><?php echo $row[1] ?></h1>
                <div class="cardData">

                <div class="col">
                <h2>Info</h2>   
                <p>ID: <?php echo $row[0] ?></p>
                <p>Job Type: <?php echo $row[2] ?></p>
                <p>Key Skills: <?php echo $row[10] ?></p>
                </div>

                <div class="col">
                <h2>Address</h3>
                <p><?php echo $row[6] ?> <br>
                <?php echo $row[7] . ", " . $row[8] . " " . $row[9]?></p>
        
                </div>

                <div class="col">
                <h2>Contact</h2>
                <p>Contact Name: <?php echo $row[3] ?></p>
                <p>Phone#: <?php echo $row[5] ?></p>
                <p>Email: <?php echo $row[4] ?></p>

                </div>

                <div class="col">
                <h2>Feedback</h2>
                <p>Rating: <?php echo $row[11] ?>/10</p>
                <p>Number of Terms: <?php echo $row[12] ?></p>
                </div>

                </div>
            </div>
        </div>
      </body>

    <?php
  }
} else {
    ?> <div>
    No results found.
  </div> <?php
}
?>

</HTML>
