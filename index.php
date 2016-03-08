<?php
ini_set("log_errors",1);
ini_set("error_log","/tmp/php-error.log");

session_start();

$gsUserMessage="";
$gsUserLoggedIn=false;

$_SESSION['loggedinuser']="";
$_SESSION['login']="";
$_SESSION['password'] ="";

if($_REQUEST['task'] == 'logout'){
	error_log($_GET['task']."clearing user data");
	session_unset();
}


function connect_db()
{
	$lsHost = "localhost";
	$lsDbuser = "root";
	$lsDbpass = "";
	$lsDb="testdb";
	$lsDblink="";
	try{
		$lsDblink= new mysqli($lsHost, $lsDbuser, $lsDbpass,$lsDb);
	}
	catch(Exception $e){
		
		
	}
	
	if(mysqli_connect_errno()) 
		{
			 printf("DB connect failed: %s\n", mysqli_connect_error());
			 exit;
		}
	if($lsDblink != null)
		return $lsDblink;
	else
		return null;
}

function isUserEmailValid($email){
	if(filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE)
		return false;
	else
		return true;
}

function encryptUserPassword($pwd)
{
	$salt="$1$";
	return crypt($pwd, $salt);
}

    if(!empty($_POST['newusersubmit']))
	{
	$lsUserFirstName = isset($_POST['FirstName'])? ucfirst($_POST['FirstName']) : '';
    $lsUserLastName = isset($_POST['LastName'])? ucfirst($_POST['LastName']) : '';
    $lsUserEmail = isset($_POST['Email'])? $_POST['Email'] : '';
	$lsUserEmailValid = isUserEmailValid($lsUserEmail);
    $lsUserPwd = isset($_POST['Password'])? $_POST['Password'] : '';
	$lsEncryptedUserPwd = encryptUserPassword($lsUserPwd);		
    $lsUserMktgSrc = isset($_POST['SrcSelect'])? $_POST['SrcSelect'] : '';
	
	
		if($lsUserFirstName !="" && $lsUserLastName !="" && $lsEncryptedUserPwd != "" && $lsUserMktgSrc != "" )
			{
				if($lsUserEmailValid)
					{
						$lsLinkToDB = connect_db();
						if($lsLinkToDB != null)
							{
								$lsSaveUserQry = $lsLinkToDB->prepare("insert into user (Firstname,Lastname,Email,Password,Mktngsrc) values (?,?,?,?,?)");
								$lsSaveUserQry->bind_param("sssss",$lsUserFirstName, $lsUserLastName, $lsUserEmail, $lsEncryptedUserPwd, $lsUserMktgSrc);
								error_log($lsUserFirstName.$lsUserLastName.$lsUserEmail.$lsUserPwd.$lsEncryptedUserPwd.$lsUserMktgSrc);
								$lsQryResult = $lsSaveUserQry->execute();
								if(mysqli_errno($lsLinkToDB))
								{
									error_log(mysqli_errno($lsLinkToDB).":".mysqli_error($lsLinkToDB));
									if(mysqli_errno($lsLinkToDB) == 1062)
									$gsUserMessage = "This email ID already exists in our records. Please enter a unique email ID";
								}
								else
								{
									if($lsSaveUserQry->affected_rows)
									{
										$_SESSION['login'] = $lsUserEmail;
										$_SESSION['password'] = $lsUserPwd;
										$_SESSION['loggedinuser'] = $lsUserFirstName." ".$lsUserLastName;
									 	$gsUserMessage="Welcome ".$_SESSION['loggedinuser'];
										$gsUserLoggedIn=true;
									}
								else
									{
										error_log("There was a problem saving your details to the database; Please try again!");
										$gsUserMessage = "There was a problem saving your details to the database; Please try again!";
									
									}
								}
						    }
						else
							{
								$gsUserMessage = "There was a problem connecting to the DB; Please try again!";
								error_log("There was a problem connecting to the DB; Please try again!");
							}
								
					}
				else
				{
					$gsUserMessage="Please enter a valid email address";	
				}
			}
		else
			{
				$gsUserMessage = "Please enter non-empty values for all the mandatory input fields!";
				error_log("Please enter non-empty values for all the mandatory input fields!");
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
                   <?php if ($gsUserLoggedIn == false)
				        { 
			                echo('<li><a href="/test/login.php">Login</a></li>');
						} 
						else
						{
							echo('<li><a href="/test/index.php?task=logout">Logout</a></li>');
					    } 
				   ?>
                    </ul>
			
					<div id="welcomemsg"><?php if($gsUserMessage != ""){echo($gsUserMessage);} ?> </div>
				
				    
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
		 
 <?php if($gsUserLoggedIn == false) : ?>
         <form id="signupform" action="" name="signupform" method="POST">      
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
                    <input type="submit" value="Submit" name="newusersubmit" id="signupsubmit" />
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
