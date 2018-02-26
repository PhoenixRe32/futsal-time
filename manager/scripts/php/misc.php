<?php 
	include_once(dirname(__FILE__)."/../../../swift-5.0.1/lib/swift_required.php");
	include_once(dirname(__FILE__)."/../../../sms/smsClass.php");
	
	function dateFormat($date)
	{
		$dateModF = substr($date, 8, 2).'-'.substr($date, 5, 2).'-'.substr($date, 0, 4);
		return $dateModF;
	}
	
	function generatePassword($times) 
	{
		$validchars = '0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		mt_srand();
		$password = '';

		for ($i = 0; $i < $times; $i++)
			$password .= $validchars[(mt_rand() % strlen($validchars))];
				
		return $password;
	}

	function sendEmail($arenaName, $date, $time, $fieldSize, $type, $desc, $email)
	{
		if ( trim($email) == '' ) return false;
		
		$headers = array();
		$headers['MATCH'] = 'From: <bookings@futsal-time.com>';
		$headers['MATCH_C'] = 'From: <bookings@futsal-time.com>';
		$headers['CHALLENGE'] = 'From: <bookings@futsal-time.com>';
		$headers['CANCEL'] = 'From: <bookings@futsal-time.com>';
		$titles = array();
		$titles['MATCH'] = 'Futsal-Time: Booking Details';
		$titles['CANCEL_M'] = 'Futsal-Time: Booking Cancellation';
		$titles['CANCEL_C'] = 'Futsal-Time: Challenge Cancellation';
		$titles['CANCEL_D'] = 'Futsal-Time: Challenge Cancellation';
		$titles['CHALLENGE_IS'] = 'Futsal-Time: Booking Details';
		$titles['CHALLENGE_AC'] = 'An opponent has been found';
		
		//$message = $Swift_Message::newInstance();
		$body = '';
		
		switch($desc)
		{
			case 'MATCH':
			case 'CHALLENGE_IS':
					$body="Booking Details for $arenaName\n-----------------------------------------\nGame Type: $fieldSize $type\nDate     : $date\nTime     : $time\n\n\t\tTo cancel your booking go to your 'Member' page.\n\n\t\tFor support, contact-us at: bookings@futsal-time.com";
					break;
			case 'CHALLENGE_AC':
					$body="Booking Details for $arenaName\n-----------------------------------------\n\nAn opponent has been found for the challenge issued.\n\nGame Type: $fieldSize $type ACCEPTED\nDate     : $date\nTime     : $time\n\n\t\tTo cancel your booking go to your 'Member' page.\n\n\t\tFor support, contact-us at: bookings@futsal-time.com";
					break;
			case 'CANCEL_M':
					$body="Cancellation for $arenaName\n-----------------------------------------\nGame Type: $fieldSize $type\nDate     : $date\nTime     : $time\n\n\t\tFor support, contact-us at: bookings@futsal-time.com";
					break;
			case 'CANCEL_C':
					$body="Cancellation for $arenaName\n-----------------------------------------\nGame Type: $fieldSize $type\nDate     : $date\nTime     : $time\n\n\t\tThe booking was cancelled from the second party so the challenge issued by the original party is still valid.\n\n\t\tFor support, contact-us at: bookings@futsal-time.com";
					break;
			case 'CANCEL_D':
					$body="Cancellation for $arenaName\n-----------------------------------------\nGame Type: $fieldSize $type\nDate     : $date\nTime     : $time\n\n\t\tWe regret to inform you that your booking was cancelled by the arena. Matches take precedence over pending challenges \n\n\t\tFor support, contact-us at: bookings@futsal-time.com";
					break;
			default:
					break;
		}
		$msg = "================================================================================\nTO: ".$email."\nFROM: ".$headers[$type]."\nTOPIC: ".$titles[$desc]."\nSUBJECT:\n========\n".$body."\n\n";
		error_log($msg,3,'../../../error_logs/emails_res.log');
		return @mail($email, $titles[$desc], $body, $headers[$type]);
	}
	
	function sendSMSGen($sender, $receiver, $sms)
	{	
		$recipients = array();
		
		if ( !is_array($receiver) )
			$recipients[]=$receiver;
		else
			$recipients=$receiver;
		
		/** 
		* Configuration Section:
		*  Set the username and password that you used during the registration
		*/
		$cfg['wsdl_file']=dirname(__FILE__)."/../../../sms/websms.wsdl";
		$cfg['username']="vhf_andrew@yahoo.gr";
		$cfg['password']='1q@W3e$R5t';
		$cfg['session_path']=dirname(__FILE__)."/../../../sms/websms.com.cy.ses";
		$cfg['session_time_to_live']=60;		
		$ws = new WebsmsClient($cfg);
		
		$auth = $ws->authenticate();
		if ( $auth['success'] != 1 )
		{
			$result['state'] = 'fail';
			$result['message'] = 'Invalid Username/Password! The sms was NOT sent.';
			return $result;
		}
		
		$remainingCredits=$ws->getCredits();
		if ( $remainingCredits < 1 )
		{
			$result['state'] = 'fail';
			$result['message'] = 'Insufficient credits! The sms was NOT sent.';
			return $result;
		}
		
		$response = $ws->submitSM($sender, $recipients, $sms, "GSM"); //$sender
		
		date_default_timezone_set("Europe/Nicosia");
		$now = new DateTime();
		$errorString = "================================================================================\n";
		$errorString .= $now->format('[Y-m-d H:i:s]');
		$errorString .= "\tSMS Send Report\n\n";
		$errorString .= "Recipients:\n-----------\n";
		$errorString .= print_r($recipients, true);
		$errorString .= "Message:\n--------\n$sms";
		$errorString .= "\nReport:\n-------\n";
		$errorString .= "Remaining Credits: $remainingCredits\n";
		$errorString .= print_r($response, true);
		$errorString .= "\n";
		error_log($errorString,3,dirname(__FILE__)."/../../../error_logs/sms_stats.log");
		
		if ( $response['status'] == 'error' )
		{
			$result['state'] = 'fail';
			$result['message'] = $response['error'].' The sms was NOT sent.';
			return $result;
		}
		else
		{
			$result['state'] = 'success';
			$result['message'] = 'The sms was sent. ['.($response['credits'] * 0.0252).'c]';
			return $result;
		}
	}
	
	function sendInfo($msg, $type, $desc, $email)
	{
		$headers = array();
		$headers['SUPPORT'] = 'From: <support@futsal-time.com>';
		$titles = array();
		$titles['REGISTER'] = 'Futsal-Time: Account validation details';
		$titles['PSWRESET'] = 'Futsal-Time: Password Reset Details';
		$body = "================================================================================\nTO: ".$email."\nFROM: ".$headers[$type]."\nTOPIC: ".$titles[$desc]."\nSUBJECT:\n========\n".$msg."\n\n";
		error_log($body,3,'../../../error_logs/emails_other.log');
		return @mail($email, $titles[$desc], $body, $headers[$type]);
	}
	
	function notifyChallenges($database, $arenaName, $date, $time, $fieldSize, $type, $desc)
	{
		$statement = "
			SELECT	email 
			FROM	customers
			WHERE	alertChallenge = '1';";
		$emails = $database->fetchSet($statement);
		if ( $database->getErrorStatus() ) { return false; }
		
		foreach ( $emails as $emailCell )
		{
			$email = $emailCell['email'];
			$headers = array();
			$headers['CHALLENGE'] = 'From: <bookings@futsal-time.com>';
			$titles = array();
			$titles['CHALLENGE_IS'] = 'Futsal-Time: Booking Details';
			
			$body="A challenge has been issued at $arenaName\n\nBooking Details for $arenaName\n-----------------------------------------\nGame Type: $fieldSize $type\nDate     : $date\nTime     : $time\n\n\t\tTo cancel your booking go to your 'Member' page.\n\n\t\tFor support, contact-us at: bookings@futsal-time.com";
			
			$msg = "================================================================================\nTO: ".$email."\nFROM: ".$headers[$type]."\nTOPIC: ".$titles[$desc]."\nSUBJECT:\n========\n".$body."\n\n";
			error_log($msg,3,'../../../error_logs/emails_res.log');
			@mail($email, $titles[$desc], $body, $headers[$type]);
		}
	}
?>