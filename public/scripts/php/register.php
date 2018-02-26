<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/languages/lang.{$_POST['lang']}.php");
	include_once(dirname(__FILE__)."/userRelatedFunctions.php");
	include_once(dirname(__FILE__)."/misc.php");
	
	$securityQuestion = $_POST['securityQuestion'];	
	if ( !empty($securityQuestion) )
	{
		$response = array(
						"state" => "fail",
						"title" => "<u>Futsal-Time</u>",
						"message" => $lang['SPAM']
					);
		echo json_encode($response);
		exit();
	}
	
	$humanVerification = $_POST['humanVerification'];
	$equVal = $_POST['equVal'];
	if ( $equVal != $humanVerification )
	{
		$response = array(
						"state" => "fail",
						"title" => "<u>Futsal-Time</u>",
						"message" => $lang['CALC']
					);
		echo json_encode($response);
		exit();
	}
	
	// Check if the email is valid accordingly to our regex.
	$email = $_POST['email']; 
	if ( preg_match("/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,8})+)$/", $email) != 1 )
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['EMAIL_INV']
					);
		echo json_encode($response);
		exit();
	}

	// Check if the phone is valid accordingly to our regex.
	$phone = $_POST['phone'];
	$discard = array(' ', '-');
	$phone = str_replace($discard,'',$phone);
	if ( preg_match("/^(99|96|97)[0-9]{6}$/", $phone) != 1 )
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['PHONE_INV_EXPL']
					);
		echo json_encode($response);
		exit();
	}
	

	// Check if the password is withing the range required.
	$password = $_POST['password'];
	if ( strlen($password) < 8 || strlen($password) > 16 )
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['PSW_INV']
					);
		echo json_encode($response);
		exit();
	}

	// Check the name is not empty.
	$name = $_POST['name'];
	if ( strlen($name) < 1)
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['REG_NAME']
					);
		echo json_encode($response);
		exit();
	}
	
	$database = new MyDBManager();
	
	// Check is the email already exists.
	if ( !emailExists($database, $email) )
	{
		if ( !phoneExists($database, $phone) )
		{
			$randomPassword=generatePassword(16);
			// If the email doens't exist add the user in the database.
			if ( addUser($database, $email, $name, $phone, $password, $randomPassword) )
			{
				$body="Follow the link below to validate your account:\nhttp://futsal-time.com/validateEmail.php?vid=$randomPassword&email=$email\nUsername=$email\nPassword=$password\nYou can change your contact information and password from the 'Member' screen at anytime.\nFor help contact us at: support@futsal-time.com\nIf this email was sent to you by mistake please ignore and delete it.";
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
				echo json_encode($response);
				
				session_regenerate_id(true);
				$_SESSION['loggedIn'] = true;
				$_SESSION['name'] = $name;
				$_SESSION['email'] = $email;
				$_SESSION['phone'] = $phone;
				$_SESSION['notification_challenges'] = false;
				$_SESSION['type'] = 'customer';
				
				$database->__destruct(); unset($database);
				exit();
			}
			else 
			{
				// If the addiotion of the user fails return failure.
				$response = array(
								'state' => 'fail',
								'title' => '<u>Futsal-Time</u>',
								'message' => $lang['ERROR_QUERY_C']
							);
				echo json_encode($response);
				
				$database->__destruct(); unset($database);
				exit();
			}
		}
		else
		{
			if ( regUserExists($database, $phone) )
			{
				$response = array(
								'state' => 'fail',
								'title' => '<u>Futsal-Time</u>',
								'message' => "<b>{$phone}</b>".$lang['REG_PHONE_EX']
							);							
				echo json_encode($response);
				
				$database->__destruct(); unset($database);
				exit();
			}
			
			if ( updateUser($database, $email, $name, $phone, $password, 'FALSE') )
			{
				$validationCode = $database->fetchCell("SELECT validated FROM customers WHERE phone = {$phone}");
				$body="Follow the link below to validate your account:\n\nhttp://www.futsal-time.com/validateEmail.php?vid=$validationCode&email=$email\n\nUsername=$email\nPassword=$password\n\nYou can change your contact information and password from the 'Member' screen at anytime.\n\nFor help contact us at: support@futsal-time.com\n\nIf this email was sent to you by mistake please ignore and delete it.";
				sendInfo($body, 'SUPPORT', 'REGISTER', $email);
				
				$response = array(
								'state' => 'success',
								'title' => '<u>Futsal-Time</u>',
								'message' => $lang['REG_SUC_EMAIL']
							);							
				echo json_encode($response);
				
				/* LOGIN ONE TIME */
				session_regenerate_id(true);
				
				$_SESSION['loggedIn'] = true;
				$_SESSION['name'] = $name;
				$_SESSION['email'] = $email;
				$_SESSION['phone'] = $phone;
				$_SESSION['notification_challenges'] = false;
				// session_write_close();
				
				$database->__destruct(); unset($database);
				exit();
			}
			else 
			{
				// If the addiotion of the user fails return failure.
				$response = array(
								'state' => 'fail',
								'title' => '<u>Futsal-Time</u>',
								'message' => $lang['ERROR_QUERY_C']
							);
				echo json_encode($response);
				
				$database->__destruct(); unset($database);
				exit();
			}
		}
	}
	else
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => preg_replace('/{email}/', $email, $lang['REG_EMAIL_EX'])
					);
		echo json_encode($response);
		
		$database->__destruct(); unset($database);
		exit();
	}
?>