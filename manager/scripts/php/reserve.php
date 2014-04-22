<?php
	include_once(dirname(__FILE__)."/levelcontrol.php");
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/reserveRelatedFunctions.php");	
	include_once(dirname(__FILE__)."/arenaRelatedFunctions.php");
	
	$arenaID = $_SESSION['arenaID'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$fieldId = $_POST['fieldId'];
	
	$duration = $_POST['duration'];
	$gameType = $_POST['type'];
	$fieldSize = $_POST['size'];
	$cost = $_POST['cost'];
	
	$customerN = $_POST['name1'];
	$customerP = $_POST['phone1'];
	$customerE = $_POST['email1'];
	$opponentN = $_POST['name2'];
	$opponentP = $_POST['phone2'];
	$opponentE = $_POST['email2'];
	
	$acceptChal = $_POST['accept'];
	
	if ( $acceptChal == 0 )
	{
		if ( !empty($customerE) )
		{
			if ( preg_match("/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,8})+)$/", $customerE) != 1 )
			{
				$response = array(
								'state' => 'fail',
								'title' => '<u>Futsal-Time</u>',
								'message' => 'The customer email you provided is invalid.<br />'
							);
				echo json_encode($response);
				exit();
			}
		}
		
		if ( !empty($customerP) )
		{
			// Check if the phone is valid accordingly to our regex.
			if ( preg_match("/^(99|96|97)[0-9]{6}$/", $customerP) != 1 )
			{
				$response = array(
								'state' => 'fail',
								'title' => '<u>Futsal-Time</u>',
								'message' => 'The customer phone provided must be local in Cyprus. This means that it must use one of the Cyprus area codes and be 6 numbers long disregarding the area/country code<br />'
							);
				echo json_encode($response);
				exit();
			}
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
		if ( !empty($opponentE) )
		{
			if ( preg_match("/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,8})+)$/", $opponentE) != 1 )
			{
				$response = array(
								'state' => 'fail',
								'title' => '<u>Futsal-Time</u>',
								'message' => 'The opponent email you provided is invalid.<br />'
							);
				echo json_encode($response);
				exit();
			}
		}
		
		if ( !empty($opponentP) )
		{
			// Check if the phone is valid accordingly to our regex.
			if ( preg_match("/^(99|96|97)[0-9]{6}$/", $opponentP) != 1 )
			{
				$response = array(
								'state' => 'fail',
								'title' => '<u>Futsal-Time</u>',
								'message' => 'The opponent phone provided must be local in Cyprus. This means that it must use one of the Cyprus area codes and be 6 numbers long disregarding the area/country code<br />'
							);
				echo json_encode($response);
				exit();
			}
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
	
	
	
	$database = new MyDBManager(); //foreach ( $_POST as $k=>$v ) echo "$k->$v \n";exit();	
	
	if ( $gameType == 'MATCH' && $acceptChal == 0 )
	{
		if ( !phoneExists($database, $customerP) )
		{
			addUnregUser($database, $customerN, $customerP);
		}
		
		$result = reservationFinalizedMatch($database, $arenaID, $date, $time, $gameType, $duration, $customerP, $opponentP, $fieldSize, $customerE, $fieldId);
		
		if ( $result['state'] == 'success' )
		{
			$statement = "
				INSERT IGNORE INTO customersRep(customerPhone, arenaId )
				VALUES ('$customerP', '$arenaID');";
			$database->runQuery($statement);	
		}
	}
	else if ( $gameType == 'CHALLENGE' && $acceptChal == 0 )
	{
		if ( !phoneExists($database, $customerP) )
		{
			addUnregUser($database, $customerN, $customerP);
		}
		
		$result = reservationFinalizedMatch($database, $arenaID, $date, $time, $gameType, $duration, $customerP, $opponentP, $fieldSize, $customerE, $fieldId);

		if ( $result['state'] == 'success' )
		{
			$statement = "
				INSERT IGNORE INTO customersRep(customerPhone, arenaId )
				VALUES ('$customerP', '$arenaID');";
			$database->runQuery($statement);	
		}
	}
	// else if ( $type == 'CHALLENGE' && $acceptChal == 0 && $challenges != 0 )
		// $result = reservationFinalizedChallenge($database, $arenaID, $date, $time, $type, $duration, $customer, $opponent, $fieldSize, $email);
	else if ( $gameType == 'CHALLENGE' && $acceptChal == 1 )
	{
		if ( !phoneExists($database, $opponentP) )
		{
			addUnregUser($database, $opponentN, $opponentP);
		}
		
		$result = reservationFinalizedChallenge($database, $arenaID, $date, $time, $gameType, $duration, $opponentP, $opponentP, $fieldSize, $opponentE, $fieldId);
		
		if ( $result['state'] == 'success' )
		{
			$statement = "
				INSERT IGNORE INTO customersRep(customerPhone, arenaId )
				VALUES ('$opponentP', '$arenaID');";
			$database->runQuery($statement);	
		}
	}
	else
	{
		$body = print_r($_POST, true);
		$database->errorManagement("reserve.php // Finalizing\n{$body}", 'Impossible Path', '');
		$result = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
					);
	}
	
	$database->__destruct(); unset($database);
	echo json_encode($result);
	exit();
?>