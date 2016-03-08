<?php
$host="localhost";
$dbusername="root";
$dbpassword="";
$db='testdb';
$login=$_POST['Email'];
$pwd=$_POST['Password'];
$firstname="";
$lastname="";



if($login != "" || $pwd != "")
{
$link= new mysqli($host, $dbusername, $dbpassword,$db);
if (mysqli_connect_errno())
	{
		echo("Count not establish db connection; Please try again!". mysqli_connect_error());
	}
	else{
		
    $qry = $link->prepare("select Firstname, Lastname from user where Email = ? and password = ?");
	$qry->bind_param("ss",$login,$pwd);
	echo('$qry');
    $res = $qry->execute();
    if($res)
	{
		$firstname = $row[0];
		$lastname = $row[1];
	}
    else 
    echo ("login details were not found; please try again!");
}
else
echo("error connecting to db!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - IFund</title>
  
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
                    <li><a href="/">Home</a></li>
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
                        <li><a href="/test/login.php">Login</a></li>
                    </ul>
					<div id="welcomemsg">Welcome <?=$firstname;?> &nbsp; <?=$lastname;?></div>
					</div>

                
            </div>
              </div>
         </div>
   
    <div class="container body-content">
        



<div class="maincont">
    <div class="center">
        <div id="mainimage">
            <img src="/test/Content/Images/stockimg.jpg" alt="ILend" />
			<div id="logincontent">
			 <form action="loginscript.php" method="POST">
                <div id="fieldgrp">
                    <label for="Email" id="loginlabel">Email</label> <br />
                    <input id="logintext" name="Email" type="text" value="" />
                </div>
                <div id="fieldgrp">
                    <label for="Password" id="loginlabel">Password</label> <br />
                    <input id="logintext" name="Password" type="password" value="" />
                </div>
                <div id="fieldgrp">
                    <input type="submit" value="Login" id="loginsubmit">
                </div>
				</form>
            </div>
        </div>
    </div>
    </div>

    </div>
	</body>
</html>
