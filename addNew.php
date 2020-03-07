<?php
include_once("dbinfo.php");

    //establish connection
    $conn = mysqli_connect($db_host, $db_user, $db_password, "work_term_employer_tracker");
    //check for successful connection

    if(!$conn) {
        die("Connection failed:" . mysqli_connect_error());
    }
    //working
   //echo "Connection new";

   //grab variables and make sure all fields are filled in and clean string
   $employer_name = "";
   $street = "";
   $city = "";
   $province = "";
   $postal_code = "";
   $contact_name = "";
   $phone = "";
   $email= "";
   $jobType = "";
   $key_skills = "";
   $num_of_terms = "";

//if method = POST or GET (whatever im using), then execute php
$insertNew = false;
$errors = false;
if ( isset( $_POST["newForm"] ) ) { 
    //retrieve variables and clean string
    $employer_name = $_POST["employer_name"];;
    $street = $_POST["street"];;
    $city = $_POST["city"];;
    $province = $_POST["province"];;
    $postal_code = $_POST["postal_code"];;
    $contact_name = $_POST["contact_name"];;
    $phone = $_POST["phone"];;
    $email= $_POST["email"];;
    $jobType = $_POST["job_type"];;
    $key_skills = $_POST["key_skills"];;
    $num_of_terms = $_POST["num_of_terms"];
    $rating = 0;

    $employer_name = mysqli_real_escape_string($conn, $employer_name);
    $street = mysqli_real_escape_string($conn, $street);
    $city = mysqli_real_escape_string($conn, $city);
    $province = mysqli_real_escape_string($conn, $province);
    $postal_code = mysqli_real_escape_string($conn, $postal_code);
    $contact_name = mysqli_real_escape_string($conn, $contact_name);
    $phone = mysqli_real_escape_string($conn, $phone);
    $email= mysqli_real_escape_string($conn, $email);
    $jobType = mysqli_real_escape_string($conn, $jobType);
    $key_skills = mysqli_real_escape_string($conn, $key_skills);
    $num_of_terms = mysqli_real_escape_string($conn, $num_of_terms);
    
    //check that none are empty?
    if ( $employer_name == null || empty($employer_name)
        || $street == null || empty($street) 
        || $city == null || empty($city) 
        || $province == null || empty($province) 
        || $postal_code == null || empty($postal_code)
        || $contact_name == null || empty($contact_name)
        || $phone == null || empty($phone)  
        || $email == null || empty($email) 
        || $jobType == null || empty($jobType) 
        || $key_skills == null || empty($key_skills) 
        || $num_of_terms == null || empty($num_of_terms)   ) { 
        $errors = true; 
    } else {
        //insert variables into database
        $sql = "INSERT INTO employer (company_name, street, city, province, postal_code, category, contact_name, phone, email, skills, rating, num_of_terms) VALUES ('$employer_name', '$street', '$city', '$province', '$postal_code', '$jobType', '$contact_name', '$phone', '$email', '$key_skills', '$rating', '$num_of_terms')";
        $result = mysqli_query($conn, $sql);

        //retrieve company id
        $sql = "SELECT employer_id FROM employer ORDER BY employer_id DESC LIMIT 1";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $employer_id = $row["employer_id"];
        }

        //print out success message at top of blank form
        $insertNew = true;
    }
}

?>

<!DOCTYPE html>
<HTML>
    <head>
        <title>
            Add new employer
        </title>
        <link rel="stylesheet" href="./style2.css">
    </head>

    <div class="logo-header">
    <div class="header-row">	
        <div class="grid1">	
            <a href="/WorkTermTracker/mainMenu.php">	
                <img src="images/nscc-logo.png" class="nscc-logo" alt="logo">	
            </a>	
        </div>	
        <div class="grid2">	
        <h2>Work Term Employer Tracker<br><br>Add New Employer</h2>	
        </div>	
    </div>
    </div>

    <body>
    <div class="addnew-page">
    <br>

        <div class="message">
        <!-- Success message here, put form back to empty. I put ID here too in case they want to add feedback right away-->
        <?php if ($insertNew == true) {echo "Success! You have entered a new company, " . $employer_name . " ID: " . $employer_id;
        $employer_name = "";
        $street = "";
        $city = "";
        $province = "";
        $postal_code = "";
        $contact_name = "";
        $phone = "";
        $email= "";
        $jobType = "";
        $key_skills = "";
        $num_of_terms = "";}
            //ERROR message here if something is missing in the form (all fields required)
        elseif ($errors == true) {echo "Error! You are missing 1 or more fields. Please check your form again.";}  ?>
        </div>
        

    <div class="form">
        <form method="POST">
        <div class="grid-container">
        <div class="grid-item">
            <h2>
                Company
            </h2>
            Company name:<br> <input type="text" name="employer_name" value="<?php echo $employer_name; ?>" required /><br />
            Street Address:<br> <input type="text" name="street" value="<?php echo $street; ?>" required /><br />
            City:<br> <input type="text" name="city" value="<?php echo $city; ?>" required /><br />
            Province:<br> <select name="province">
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
            </select><br />
            Postal Code:<br> <input type="text" name="postal_code" value="<?php echo $postal_code; ?>" required /><br />
        </div>
        <div class="grid-item">
            <h2>
                Contact
            </h2>
            Name of Contact:<br> <input type="text" name="contact_name" value="<?php echo $contact_name; ?>" required /><br />
            Phone Number:<br> <input type="text" name="phone" value="<?php echo $phone; ?>" required /><br />
            Email:<br> <input type="text" name="email" value="<?php echo $email; ?>" required /><br />
        </div>

        <div class="grid-item2">
            <h2>
                Work Term Details
            </h2>
            Job Type:<br> <select name="job_type">
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
                </select> <br />
            Numer of Work Terms Completed:<br> <input type="number" name="num_of_terms" min="1" max="9000" value="<?php echo $num_of_terms; ?>"><br />
            <div class="subitem">
            Key Skills:<br> <textarea name="key_skills" rows="10" cols="70" placeholder="ex: leadership" required ><?php echo $key_skills; ?></textarea><br />
            </div>
            </div>

        </div>
            <div class="submit">
            <input type="submit" name="newForm" class="submitBtn" value="Add Employer" /><br />
            </div>
        </form>
        </div>
        </div>
    </body>
</HTML>