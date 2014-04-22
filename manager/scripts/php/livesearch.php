<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	
	$about = $_POST['about'];
	$q = $_POST['keyword'];	
	$arenaID = $_POST['arenaID'];
	$response = "<table class='display_box' align='left' style='background-color:white; color:blue; border-style:none;'>";
	
	$database = new MyDBManager();
		
	$statement = "
		SELECT email, name, phone, reputation 
		FROM customers
		INNER JOIN customersRep ON customerPhone = phone
		WHERE phone like '$q%' AND arenaId = $arenaID;";
	$customers = $database->fetchSet($statement);
	if ( $database->getErrorStatus() ) { 
		$response .= "</table>";
		echo $response;
		exit();
	}
	
	foreach ( $customers as $p )
	{
		$phone = $p['phone'];
		$fullname = addslashes($p['name']);
		$email = addslashes($p['email']);
			
		$und_q = '<b>'.$q.'</b>';
		$finalPhone = str_ireplace($q, $und_q, $phone);
		
		if ( $p['reputation'] == 'BAD' )
			$response .= "<tr><td style='background-color:FireBrick; cursor: pointer; font-size:0.8em' onclick=\"fillInfo('$about', '$fullname', '$email', '$finalPhone')\">$finalPhone &nbsp $fullname &nbsp $email</td></tr>";
		else if ( $p['reputation'] == 'GOOD' )
			$response .= "<tr><td style='background-color:Gold; cursor: pointer; font-size:0.8em' onclick=\"fillInfo('$about', '$fullname', '$email', '$finalPhone')\">$finalPhone &nbsp $fullname &nbsp $email</td></tr>";
		else
			$response .= "<tr><td style='background-color:Gainsboro; cursor: pointer; font-size:0.8em' onclick=\"fillInfo('$about', '$fullname', '$email', '$finalPhone')\">$finalPhone &nbsp $fullname &nbsp $email</td></tr>";
	}
	
	$response .= "</table>";
	echo $response;
	exit();
?>