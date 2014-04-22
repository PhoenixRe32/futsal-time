<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/languages/lang.{$_POST['lang']}.php");
	include_once(dirname(__FILE__)."/userRelatedFunctions.php");

	// Check if the email is valid accordingly to our regex.
	$email = $_POST['login_email'];
	if ( preg_match("/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,8})+)$/", $email) != 1 )
	{
		$response = array(
						'state' => 'fail',
						'message' => $lang['EMAIL_INV']
					);
		echo json_encode($response);
		exit();
	}
	
	$database = new MyDBManager();
	
	// Check if the email already exists in our database.
	if ( !emailExists($database, $email) )
	{
		$response = array(
						'state' => 'fail',
						'message' => $lang['EMAIL_UNREG']
					);
		echo json_encode($response);
		$database->__destruct(); unset($database);
		exit();
	}
	else
	{
		// Check if the password is withing the range required.
		$password = $_POST['login_password'];
		if ( strlen($password) < 8 || strlen($password) > 16 )
		{
			$response = array(
							'state' => 'fail_psw',
							'message' => $lang['PSW_INV']
						);
			echo json_encode($response);
			$database->__destruct(); unset($database);
			exit();
		}
		
		// If the email exists then chack that the password matches with the one stored and return the details
		// of the user.
		$details = passwordMatches($database, $email, $password);
		$database->__destruct(); unset($database);
		// If no details were returned fail the login.
		if ( $details === null ) 
		{
			$response = array(
							'state' => 'fail_psw',
							'message' => $lang['PSW_INC']
						);
			echo json_encode($response);
			exit();
		}
		else 
		{
			if ( !$details['validated'] ) 
			{
				$response = array(
								'state' => 'fail',
								'message' => $lang['VAL_ACC']
							);
				echo json_encode($response);
				exit();
			}	
			else
			{
				// If fetails were returned then the user was validated so the session id is regenerated the login state
				// of the user changed and his details are stored in the session.
				session_start();
				session_regenerate_id(true);
				$_SESSION['loggedIn'] = true;
				$_SESSION['name'] = $details['name'];
				$_SESSION['email'] = $details['email'];
				$_SESSION['phone'] = $details['phone'];
				$_SESSION['notification_challenges'] = $details['alertChallenge'];
				$_SESSION['type'] = 'customer';
				$response = array(
								'state' => 'success',
								'message' => 'Login successful.',
								'name' => $details['name'],
								'email' => $details['email'],
								'phone' => $details['phone'],
							);
				echo json_encode($response);
				exit();
			}
		}
	}
?>