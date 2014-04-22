<?php	
	include_once(dirname(__FILE__)."/scripts/php/databaseClass.php");
	
	$vid = $_GET['vid']; 
	$email = $_GET['email'];

	$database = new MyDBManager();
	
	$statement = "
		UPDATE customers
		SET validated='VALIDATED' 
		WHERE email='$email' 
		AND validated='$vid'";
	$database->runQuery($statement);
	if ( !$database->getErrorStatus() )
	{
	?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<meta http-equiv="Refresh" content="3;URL=./index.php?caller=index"/>
			<title>VALIDATED</title>
		</head>
		<body>
			<h1>Your account has been succesfully validated.</h1>
			<h4>&nbsp You will be shortly redirected to the home page...</h4>					
			<h4>&nbsp If you are not being redirected automatically <a href='./index.php?caller=index'>click here</a></h4>
			</body>
		</html>
	<?php
	}
	else
	{
	?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<meta http-equiv="Refresh" content="4;URL=./index.php?caller=index"/>
			<title>NOT VALIDATED</title>
		</head>
		<body>
			<h1>There was a problem validating your account. Please try again.</h1>
			<h4>&nbsp You will be shortly redirected to the home page...</h4>					
			<h4>&nbsp If you are not being redirected automatically <a href='./index.php?caller=index'>click here</a></h4>
		</body>
		</html>
	<?php
	}
	$database->__destruct(); unset($database);
?>