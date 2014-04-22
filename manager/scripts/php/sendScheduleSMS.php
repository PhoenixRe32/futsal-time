<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/misc.php");

	parse_str($_POST['args']);
	
	if ( preg_match("/^(99|96|97)[0-9]{6}$/", $mobile) != 1 )
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => "The mobile phone number used was invalid. ({$mobile})"
					);
		echo json_encode($response);
		exit();
	}
	
	$database = new MyDBManager();
		
	$statement = "
		SELECT	time, field, gameType, fieldSize, customer, name
		FROM	reservations
		INNER JOIN customers ON customer = phone
		WHERE	date = '{$sqlDate}' AND arena = '${nick}'
		ORDER BY time ASC;";
		
	$daySchedule = $database->fetchSet($statement);
	
	if ( $database->getErrorStatus() )
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
					);
		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
	}
	else
	{
	// 12 chars for date
	// 15+name+24+[5, 7, 9]+5 chars for each reservation
		$sms = substr($norDate,0,10)."
";
		foreach ( $daySchedule as $res )
		{
			$sms .= "
".substr($res['time'],0,5)."
{$res['name']} ({$res['customer']})
{$res['field']}
{$res['gameType']}
~~~~~
";
		}

		$response = sendSMSGen($nick, $mobile, $sms); // TODO: $response = sendSMSGen($nick, $mobile, $sms);
		$response['title'] = '<u>Futsal-Time</u>';
		
		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
	}
?>