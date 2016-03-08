<?php

$host = "localhost";
$dbuser = "root";
$dbpass = "";
$db="testdb";
$fname = $_POST['FirstName'];
$lname = $_POST['LastName'];
$email = $_POST['Email'];
$pwd = $_POST['Password'];
$mktsrc = $_POST['SrcSelect'];

if($fname != "" && $lname != "" && $email != "" && $pwd != "" && $mktsrc != "")
{
	$link= mysqli_connect($host, $dbuser, $dbpass,$db);
	
	if (mysqli_connect_errno())
	{
		echo("Count not establish db connection; Please try again!". mysqli_connect_error());
	}
	else
	{
		$qry = $link->prepare("insert into user (Firstname,Lastname,Email,Password,Mktngsrc) values (?,?,?,?,?)");
		$qry->bind_param("sssss",$fname, $lname, $email, $pwd, $mktsrc);
		$result = $qry->execute();
		if($result)
		{
			echo("Your data was successfully saved into the user table!!");
			$row = mysqli_fetch_row($result);
			echo "$row[0]";
		}
		else
		{
			echo "There was en error in executing the query";
		}
	}
}
else
{

	echo "Please enter non-empty values for all the mandatory input fields!";
}