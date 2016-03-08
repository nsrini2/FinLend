<!DOCTYPE html>

<?php
ini_set("log_errors",1);
ini_set("error_log","/tmp/php-error.log");

session_start();
$gsUserMessage="";
$gsUserLoggedIn=false;

$_SESSION['loggedinuser']="";
$_SESSION['login']="";
$_SESSION['password']="";

if($_REQUEST['task'] == 'logout')
{
	error_log($_GET['task']."clearing data");
	session_unset();
}

function connect_db()
{
	$lsHost="localhost";
	$lsDbUser="root";
	$lsDbPass="";
	$lsDb="testdb";
	$lsDbLink="";
	try
	{
		$lsDbLink=new mysqli($lsHost, $lsDbUser, $lsDbPass,$lsDb);
	}
	catch(Exception $e)
	{
		;
	}
	if(mysqli_connect_errno()){
		printf("could not connect to DB".mysqli_connect_errno());
		exit;
	}
	
	if($lsDbLink != null)
		return $lsDbLink;
	else
		return null;
		
}

function isUserEmailValid($email)
{
	if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
		return false;
	else
		return true;
}

function hashPwd($pwd)
{
	$salt="$1$";
	return crypt($pwd, $salt);
}

if(!empty($_POST['loginsubmit']))
{
	$lsLogin=isset($_POST['Email'])? $_POST['Email'] : "";
	$lsPassword = isset($_POST['Password'])? $_POST['Password'] : "";
	$lsHashPwd = hashPwd($lsPassword);
	
	if($lsLogin != "" && $lsPassword != "")
	{
		if(isUserEmailValid($lsLogin))
		{
			$lsLinkToDb = connect_db();
			if($lsLinkToDb != null)
			{
				$lsCheckLoginQry = $lsLinkToDb -> prepare("select FirstName, LastName from user where Email = ? && Password = ?");
				$lsCheckLoginQry->bind_param("ss",$lsLogin,$lsHashPwd);
				$lsQryResult = $lsCheckLoginQry->execute();
				if(mysqli_errno($lsLinkToDb))
				{
					error_log(mysqli_errno($lsLinkToDb).":".mysqli_error($lsLinkToDb));
				}
				else
				{
					$lsCheckLoginQry->bind_result($lsfn, $lsln);
					$lsCheckLoginQry->fetch();
					if($lsfn != null && $lsln != null)
					{
						//$lsRes = $lsCheckLoginQry->get_result();
						//$lsRow = $lsRes->fetch_row();
						$_SESSION['login'] = $lsUserEmail;
						$_SESSION['password'] = $lsUserPwd;
						$_SESSION['loggedinuser'] = $lsfn." ".$lsln;
						$gsUserMessage="Welcome ".$_SESSION['loggedinuser'];
						$gsUserLoggedIn=true;
					}
					else
					{
						error_log("We could not find your details in our database; please try again".mysqli_error($lsLinkToDb));
						$gsUserMessage="We could not find your details in our database; please try again";
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
					<?php if($gsUserLoggedIn == true) :?>
					  <li><a href="/Home/Profile">Create Profile</a></li>
					<?php endif; ?>
                    <li><a href="/Home/Borrow">Borrow</a></li>
                    <li><a href="/Home/About">Lend</a></li>
    <!--            <li><a href="/Home/About">Merchant</a></li> -->
                    <li><a href="/Home/About">Market Place</a></li>  
                </ul>


                <div id="topright">
                    <ul id="toprightmenu">
                        <li><a href="/Home/About">How It Works</a></li>
                        <li><a href="/Home/Careers">Careers</a></li>
                        <li><a href="/Home/About">Contact Us</a></li>
                   <?php if ($gsUserLoggedIn == false)
				        { 
			                echo('<li><a href="/test/index.php">Login</a></li>');
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
     <div id="logincontent">
         <form id="loginform" name="loginform" action="" method="POST">
                <div id="fieldgrp">
                    <label for="Email" id="loginlabel">Email</label> <br />
                    <input id="logintext" name="Email" type="text" value="" />
                </div>
                <div id="fieldgrp">
                    <label for="Password" id="loginlabel">Password</label> <br />
                    <input id="logintext" name="Password" type="password" value="" />
                </div>
                <div id="fieldgrp">
                    <input type="submit" name="loginsubmit" value="Login" id="loginsubmit">
                </div>
		</form>
     </div>
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
