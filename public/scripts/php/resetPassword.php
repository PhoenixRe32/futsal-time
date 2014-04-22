<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/languages/lang.{$_POST['lang']}.php");
	include_once(dirname(__FILE__)."/misc.php");
	
	$email = $_POST['email'];
	$randomPassword=generatePassword(8);
	$hash = crypt($randomPassword, '$6$rounds=8888$'.$email.'$');
		

	$database = new MyDBManager();
	$statement = "
		UPDATE customers 
		SET password='$hash'
		WHERE email='$email'";
	$database->runQuery($statement);
	if ( $database->getErrorStatus() )
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['ERROR_QUERY']
					);
						
		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
	}
	
	$body="Your Password has been changed:\n\nUsername=$email\nPassword=$randomPassword\n\nYou can change your contact information and password from the 'Member' screen at anytime.\n\nFor help contact us at: support@futsal-time.com";		
	if ( sendInfo($body, 'SUPPORT', 'PSWRESET', $email) )
	{		
		$response = array(
						'state' => 'success',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['PSW_RES']
					);
		
		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
	}
	else
	{		
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['PSW_RES_NO_EMAIL']
					);
						
		$database->__destruct(); unset($database);						
		echo json_encode($response);
		exit();
	}
?>