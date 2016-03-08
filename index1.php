<?php
ini_set("log_errors",1);
ini_set("error_log","/tmp/php-error.log");

session_start();
$host = "localhost";
$dbuser = "root";
$dbpass = "";
$db="testdb";
$firstname="";
$lastname="";
$db="testdb";
$_SESSION['loggedin'] = "false";
$fname="";
$lname="";
$email="";
$pwd = "";
$mktsrc="";
$valemail="";
$message="";


if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$fname = isset($_POST['FirstName'])? ucfirst($_POST['FirstName']) : '';
    $lname = isset($_POST['LastName'])? ucfirst($_POST['LastName']) : '';
    $email = isset($_POST['Email'])? $_POST['Email'] : '';
    $pwd = isset($_POST['Password'])? $_POST['Password'] : '';
    $mktsrc = isset($_POST['SrcSelect'])? $_POST['SrcSelect'] : '';
	$valemail = filter_var($email, FILTER_VALIDATE_EMAIL);
	
	

if($fname != "" && $lname != "" && $email != "" && $pwd != "" && $mktsrc != "" )
{
	if($valemail)
	{
	$link= mysqli_connect($host, $dbuser, $dbpass,$db);
	
	if (mysqli_connect_errno())
	{
		echo("Count not establish db connection; Please try again!". mysqli_connect_error());
	}
	else
	{
		$salt="$1$";
		$epwd=crypt($pwd,$salt);
		$qry = $link->prepare("insert into user (Firstname,Lastname,Email,Password,Mktngsrc) values (?,?,?,?,?)");
		$qry->bind_param("sssss",$fname, $lname, $email, $epwd, $mktsrc);
		$result = $qry->execute();
		if($result)
		{
			echo("Your data was successfully saved into the user table!!");
			$row = mysqli_fetch_row($result);
			$message = "Welcome $fname $lname";
			$login="true";
			$_SESSION['username'] = "$fname $lname";
			$_SESSION['login'] = "$email";
			$_SESSION['password'] = "$pwd";
            $_SESSION['loggedin'] = "true";		
		}
		else
		{
			echo ("There was a problem saving to the database");
		}
	}
	}
	else{
		
		$message="Please enter a valid email address";		
	}
}
else
{
	echo "Please enter non-empty values for all the mandatory input fields!";
}
}

?>

<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page - IFund</title>
  
    <script src="/test/Scripts/ThirdParty/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script src="/test/Scripts/ThirdParty/jquery.cycle2.min.js" type="text/javascript"></script>
    <link href="/test/Content/bootstrap.css" rel="stylesheet"/>
    <link href="/test/Content/site.css" rel="stylesheet"/>

 

</head>
<body>
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="header-content">
            <div class="navbar-collapse collapse">
                <ul id="mainmenu">
                    <li><img src="/test/Content/Images/logo.jpg" alt="ILend" id="logoimg" /></li>
                    <li><a href="/test/index.php">Home</a></li>
                    <li><a href="/Home/Borrow">Borrow</a></li>
                    <li><a href="/Home/About">Lend</a></li>
                    <li><a href="/Home/About">Merchant</a></li>
                    <li><a href="/Home/About">Market Place</a></li>  
                </ul>


                <div id="topright">
                    <ul id="toprightmenu">
                        <li><a href="/Home/About">How It Works</a></li>
                        <li><a href="/Home/Careers">Careers</a></li>
                        <li><a href="/Home/About">Contact Us</a></li>
                   <?php if ($_SESSION['loggedin'] == "false")
				        { 
			                echo('<li><a href="/test/login.php">Login</a></li>');
						} 
						else
						{
							echo('<li><a href="/test/index.php">Logout</a></li>');
					    } 
				   ?>
                    </ul>
			
					<div id="welcomemsg"><?php if($message != ""){echo($message);} ?> </div>
				
				    
                </div>

                
            </div>
              </div>
         </div>
   
    <div class="container body-content">   
<script src="/test/Scripts/FrontEnd/Index.js" type="text/javascript"></script>
<link rel="stylesheet" href="/Content/demo-slideshow.css" type="text/css" />
<div class="maincont">
    <div class="center">
       
        <div id="imgleft">
           
            <img src="/test/Content/Images/hi1.jpg" alt="ILend" />
            <img src="/test/Content/Images/hi2.jpg" alt="ILend" />
            <img src="/test/Content/Images/hi3.jpg" alt="ILend" />
            <img src="/test/Content/Images/hi4.jpg" alt="ILend" />
            <img src="/test/Content/Images/hi5.jpg" alt="ILend" />
            <div class="cycle-pager"></div>
         </div>
		 
 <?php if($_SESSION['loggedin'] == "false" ) : ?>
         <form id="signupform" action="" method="POST">      
            <div id="signupcontent">
                <div id="toplabel">
                    <label for="First_Name" id="signuplabel">First Name</label>
                    <label for="Last_Name" id="signuplabel">Last Name</label>
                </div>

                <div id="toptext">
                    <input id="signuptext" name="FirstName" type="text" value="" />
                    <input id="signuptext" name="LastName" type="text" value="" />
                </div>
                <div id="fieldgrp">
                    <label for="Email" id="biggerlabel">Email</label>
                    <input id="biggertext" name="Email" type="text" value="" />
                </div>
                <div id="fieldgrp">
                    <label for="Password" id="biggerlabel">Password</label>
                    <input id="biggertext" name="Password" type="password" value="" />
                </div>
                <div id="fieldgrp">
                    <label for="How_did_you_hear_about_us_" id="signuplabel">How did you hear about us?</label>
                    <select id="mktdd" name="SrcSelect"><option value="Newspaper">Newspaper</option>
<option value="Internet">Internet</option>
<option value="Radio">Radio</option>
<option value="Television">Television</option>
<option value="Friend">Friend</option>
</select>
                </div>
                <div id="fieldgrp">
                    <input checked="checked" id="tccheck" name="Tou" type="checkbox" value="true" /><input name="Tou" type="hidden" value="false" />
                    <label for="I_agree_to_the_Terms_of_Use_and_Privacy_policy" id="longlabel">I agree to the Terms of Use and Privacy policy</label>
                </div>
                <div id="fieldgrp">
                    <input type="submit" value="Submit" id="signupsubmit" />
                </div>
            </div>
			</form>
<?php else : ?>
          <div id="signupcontent">
		       <img src="/test/Content/Images/image3.png" alt="finlend"/>
		  </div>
<?php endif; ?>

        </div>
    </div>

    </div>

</body>
</html>
