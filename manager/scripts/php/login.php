<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/arenaRelatedFunctions.php");

	$arena = $_POST['login_arena'];
	$name = $_POST['login_name'];
	// Check if the password is withing the range required.
	$password = $_POST['login_password'];
	if ( strlen($password) < 8 || strlen($password) > 16 )
	{
		$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => 'Invalid password.'
						);
		echo json_encode($response);
		exit();
	}
	
	$database = new MyDBManager();
	
	// Check if the name already exists in our database.
	if ( ($arenaID = arenaExists($database, $arena)) == false )
	{
		$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => 'Inexistent arena name.'
						);
		echo json_encode($response);
		$database->__destruct(); unset($database);
		exit();
	}
	
	// Check if the name already exists in our database.
	if ( ($manID = nameExists($database, $name, $arenaID)) == false )
	{
		$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => 'Inexistent user name.'
						);
		echo json_encode($response);
		$database->__destruct(); unset($database);
		exit();
	}
	
	// If the name exists then chack that the password matches with the one stored and return the details
	// of the user.
	$details = passwordMatches($database, $manID, $arenaID, $name, $password);
	// If no details were returned fail the login.
	if ( $details === null ) 
	{
		$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "Incorrect password."
						);
		echo json_encode($response);
		$database->__destruct(); unset($database);
		exit();
	}
	else 
	{
		// If fetails were returned then the user was validated so the session id is regenerated the login state
		// of the user changed and his details are stored in the session.
		
		//Start session
		session_start();
		session_regenerate_id(true);
		// $_SESSION['initiated'] = true;
		$_SESSION['loggedIn'] = true;
		
		$_SESSION['arenaID'] = $details['id'];
		$_SESSION['nick'] = $details['nick'];
		$_SESSION['name'] = $name;
		
		$_SESSION['bl'] = $details['bookingLimit'];
		$_SESSION['bl_P'] = $details['bl'];
		
		$_SESSION['p'] = $details['phones'];
		$_SESSION['p_P'] = $details['p'];
		
		$_SESSION['sfC'] = $details['slotFormatClient'];
		$_SESSION['sfC_P'] = $details['sfC'];
		
		$_SESSION['sfM'] = $details['slotFormatManager'];
		$_SESSION['sfM_P'] = $details['sfM'];
		
		$_SESSION['rr'] = $details['refreshRate'];
		$_SESSION['rr_P'] = $details['rr'];
		
		$_SESSION['smsC'] = $details['smsCustomer'];
		$_SESSION['smsC_P'] = $details['smsC'];
		
		$_SESSION['smsM'] = $details['smsManager'];
		$_SESSION['smsM_P'] = $details['smsM'];
		
		$_SESSION['smsP'] = $details['smsContact'];
		$_SESSION['smsP_P'] = $details['smsP'];
	
		$_SESSION['type'] = 'manager';
		$response = array(
							'state' => 'success',
							'title' => '<u>Futsal-Time</u>',
							'message' => 'Login successful.'
						);
		echo json_encode($response);//var_dump($_SESSION);
		$database->__destruct(); unset($database);
		exit();
	}
?>