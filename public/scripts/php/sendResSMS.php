<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/misc.php");

	parse_str($_POST['args']);
		
	$database = new MyDBManager();
	
	$statement = "
		SELECT	name, smsContact as smsP, smsCustomer as smsC, smsManager as smsM
		FROM	arenas 
		INNER JOIN arenaSettings ON arenas.id = arenaSettings.id
		WHERE	arenas.id={$arenaID};";
	$smsDetails = $database->fetchRow($statement);
	if ( $database->getErrorStatus() )
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => "Couldn't get arena sms details"
					);
		return $response;
	}
	
	$database->__destruct(); unset($database);
	
	$receiver = array();
	if ( $smsDetails['smsM'] ) $receiver[] = $smsDetails['smsP'];
	$sms = '';
	
	if ( $acceptChal == 0 )
	{
		if ( !empty($customerP) )
		{
			// Check if the phone is valid accordingly to our regex.
			if ( preg_match("/^(99|96|97)[0-9]{6}$/", $customerP) != 1 )
			{
				$response = array(
								'state' => 'fail',
								'title' => '<u>Futsal-Time</u>',
								'message' => 'The customer phone provided was not a valid Cyprus mobile number.<br />'
							);
				echo json_encode($response);
				exit();
			}
			if ( $smsDetails['smsC'] ) $receiver[] = $customerP;
			$sms .= "{$mode} @ {$date}
~~~~~
{$time}
{$customerN} ({$customerP})
{$fieldSize}
{$fieldId} ()
{$gameType}";
		}
		else
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => 'No customer phone was provided.'
						);
			echo json_encode($response);
			exit();
		}
	}
	else if ( $acceptChal == 1 )
	{
		if ( !empty($customerP) )
		{
			// Check if the phone is valid accordingly to our regex.
			if ( preg_match("/^(99|96|97)[0-9]{6}$/", $customerP) != 1 )
			{
				$response = array(
								'state' => 'fail',
								'title' => '<u>Futsal-Time</u>',
								'message' => 'The customer phone provided was not a valid Cyprus mobile number.<br />'
							);
				echo json_encode($response);
				exit();
			}
			if ( $smsDetails['smsC'] ) $receiver[] = $customerP;
		}
		else
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => 'No customer phone was provided.'
						);
			echo json_encode($response);
			exit();
		}
		
		if ( !empty($opponentP) )
		{
			// Check if the phone is valid accordingly to our regex.
			if ( preg_match("/^(99|96|97)[0-9]{6}$/", $opponentP) != 1 )
			{
				$response = array(
								'state' => 'fail',
								'title' => '<u>Futsal-Time</u>',
								'message' => 'The opponent phone provided was not a valid Cyprus mobile number.<br />'
							);
				echo json_encode($response);
				exit();
			}
			if ( $smsDetails['smsC'] ) $receiver[] = $opponentP;
			$sms .= "{$mode} @ {$date}
~~~~~
{$time}
{$customerN} ({$customerP})
{$opponentN} ({$opponentP})
{$fieldSize}
{$fieldId} ()
{$gameType}";
		}
		else
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => 'No opponent phone was provided.'
						);
			echo json_encode($response);
			exit();
		}
	}
	
	$response = sendSMSGen($smsDetails['name'].'-BOOKING', $receiver, $sms); // TODO: $response = sendSMSGen($smsDetails['nick'], $receiver, $sms);
	$response['title'] = '<u>Futsal-Time</u>';
		
	echo json_encode($response);
	exit();
?>