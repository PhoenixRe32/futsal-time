<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/languages/lang.{$_POST['lang']}.php");
	include_once(dirname(__FILE__)."/userRelatedFunctions.php");
	include_once(dirname(__FILE__)."/misc.php");
	
	$database = new MyDBManager();
	$email = $_POST['email'];		
	$randomPassword=generatePassword(16);
	$psw = generatePassword(8);
	
	if ( updateUserLostValidation($database, $email, $psw, $randomPassword) )
	{
		$body="Follow the link below to validate your account:\n\nhttp://www.futsal-time.com/validateEmail.php?vid=$randomPassword&email=$email\n\nUsername=$email\nPassword=$psw\n\nYou can change your contact information and password from the 'Member' screen at anytime.\n\nFor help contact us at: support@futsal-time.com\n\nIf this email was sent to you by mistake please ignore and delete it.";
		if ( sendInfo($body, 'SUPPORT', 'REGISTER', $email) )
		{
			$response = array(
							'state' => 'success',
							'title' => '<u>Futsal-Time</u>',
							'message' => $lang['REG_SUC_EMAIL']
						);
		}
		else
		{
			$response = array(
							'state' => 'success',
							'title' => '<u>Futsal-Time</u>',
							'message' => $lang['REG_SUC_NO_EMAIL']
						);
		}
	}
	else
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['ERROR_QUERY']
					);
	}
	
	$database->__destruct(); unset($database);
	echo json_encode($response);
?>