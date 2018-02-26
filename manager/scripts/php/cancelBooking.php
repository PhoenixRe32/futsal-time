<?php
	include_once(dirname(__FILE__)."/levelcontrol.php");
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/misc.php");
	//include_once("sendSMS.php");
	
	$reservationID = $_POST['reservationID'];

	$database = new MyDBManager();
	$statement = "
		SELECT	arena, date, time, field, gameType, fieldSize, customer, opponent, name
		FROM	reservations
		INNER JOIN	arenas ON reservations.arena = arenas.nick 
		WHERE	reservations.id = {$reservationID};";
	$reservationDetails = $database->fetchRow($statement);
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
	
	
	$statement = "
		SELECT	name
		FROM	customers
		WHERE	phone = '{$reservationDetails['customer']}';";
	$reservationDetails['customerN'] = $database->fetchCell($statement);
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
	
	if ( trim($reservationDetails['opponent']) != '' )
	{
		$statement = "
			SELECT	name
			FROM	customers
			WHERE	phone = '{$reservationDetails['opponent']}';";
		$reservationDetails['opponentN'] = $database->fetchCell($statement);
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
		$reservationDetails['accept'] = 1;
	}
	else
	{
		$reservationDetails['opponentN'] = '';
		$reservationDetails['accept'] = 0;
	}
	// GET LOCK
	$statement = "SELECT GET_LOCK('futsalti_{$reservationDetails['arena']}',10);";
	$lock = $database->fetchCell($statement);
	if ( $database->getErrorStatus() ) 
	{		
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
					);
		echo json_encode($response);
		exit();
	}
	
	//lck could not be acquired, exit script
	if ( $lock == 0 )
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => "Database server was busy.<br /><br />Sorry for the inconvenience. Try again."
					);
		echo json_encode($response);
		exit();
	}
		
		if ( ( $reservationDetails['gameType'] == 'MATCH' || $reservationDetails['gameType'] == 'MATCH_C' ) && 
				( $reservationDetails['fieldSize'] == '5X5' || $reservationDetails['fieldSize'] == '6X6' ))
		{
			$past = DateTime::createFromFormat('Y-m-d H:i:s', $reservationDetails['date'].' '.$reservationDetails['time']);//var_dump($past);
			$future = DateTime::createFromFormat('Y-m-d H:i:s', $reservationDetails['date'].' '.$reservationDetails['time']);//var_dump($future);
			$interval = new DateInterval("PT15M");
			$future->add($interval);
			$past->sub($interval);

			$statement = "
				UPDATE	game_slots_{$reservationDetails['arena']}
				SET		game = 'N',
						status = 'E'
				WHERE	date = '{$reservationDetails['date']}' AND 
						time = '{$reservationDetails['time']}' AND
						field= {$reservationDetails['field']};";
			$database->runQuery($statement);
			
			for ( $i = 0; $i < 3; $i++ )
			{
				$pastdate = $past->format('Y-m-d');
				$pasttime = $past->format('H:i:s');
				$statement = "
					SELECT	field, game, status
					FROM	game_slots_{$reservationDetails['arena']}
					WHERE	date = '{$pastdate}' AND 
							time = '{$pasttime}' AND
							field= {$reservationDetails['field']};";
				$rowSlot = $database->fetchRow($statement);
				
				if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'E' )
					$statement = "
						UPDATE	game_slots_{$reservationDetails['arena']}
						SET		game = 'N',
								status = 'E'
						WHERE	date = '{$pastdate}' AND 
								time = '{$pasttime}' AND
								field= {$reservationDetails['field']};";
				else if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'E_O' )
					$statement = "
						UPDATE	game_slots_{$reservationDetails['arena']}
						SET		game = 'D',
								status = 'O'
						WHERE	date = '{$pastdate}' AND 
								time = '{$pasttime}' AND
								field= {$reservationDetails['field']};";
				else if ( $rowSlot['game'] == 'U' )
					;
				else
					$database->errorManagement("reserveRelatedFunction.php //", 'Impossible Path', "{$rowSlot['game']}, {$rowSlot['status']}, $pastdate, $pasttime, {$reservationDetails['field']}");
					
				$database->runQuery($statement);
				
				$past->sub($interval);
			}
				
			for ( $i = 0; $i < 3; $i++ )
			{
				$futuredate = $future->format('Y-m-d');
				$futuretime = $future->format('H:i:s');
				$statement = "
					SELECT	field, game, status
					FROM	game_slots_{$reservationDetails['arena']}
					WHERE	date = '{$futuredate}' AND 
							time = '{$futuretime}' AND
							field= {$reservationDetails['field']};";
				$rowSlot = $database->fetchRow($statement);
				
				if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'O' )
					$statement = "
						UPDATE	game_slots_{$reservationDetails['arena']}
						SET		game = 'N',
								status = 'E'
						WHERE	date = '{$futuredate}' AND 
								time = '{$futuretime}' AND
								field= {$reservationDetails['field']};";
				else if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'E_O' )
					$statement = "
						UPDATE	game_slots_{$reservationDetails['arena']}
						SET		game = 'D',
								status = 'E'
						WHERE	date = '{$futuredate}' AND 
								time = '{$futuretime}' AND
								field= {$reservationDetails['field']};";
				else if ( $rowSlot['game'] == 'U' )
					;
				else
					$database->errorManagement("reserveRelatedFunction.php //", 'Impossible Path', "{$rowSlot['game']}, {$rowSlot['status']}, $pastdate, $pasttime, {$reservationDetails['field']}");
					
				$database->runQuery($statement);
				
				$future->add($interval);
			}
		}
		else if ( $reservationDetails['gameType'] == 'MATCH' && 
				( $reservationDetails['fieldSize'] == '7X7' || $reservationDetails['fieldSize'] == '8X8' || 
					$reservationDetails['fieldSize'] == '9X9' || $reservationDetails['fieldSize'] == '10X10' ) )
		{
			$statement = "
				SELECT	containing
				FROM	fields_{$reservationDetails['arena']}
				WHERE	fieldId = {$reservationDetails['field']};";
			$doublesString = $database->fetchCell($statement);
			$doubleFields = explode(',', $doublesString);
			foreach ( $doubleFields as $field )
			{
				$past = DateTime::createFromFormat('Y-m-d H:i:s', $reservationDetails['date'].' '.$reservationDetails['time']);//var_dump($past);
				$future = DateTime::createFromFormat('Y-m-d H:i:s', $reservationDetails['date'].' '.$reservationDetails['time']);//var_dump($future);
				$interval = new DateInterval("PT15M");
				$future->add($interval);
				$past->sub($interval);

				$statement = "
				UPDATE	game_slots_{$reservationDetails['arena']}
				SET		game = 'N',
						status = 'E'
				WHERE	date = '{$reservationDetails['date']}' AND 
						time = '{$reservationDetails['time']}' AND
						field= {$field};";
				$database->runQuery($statement);
									
				for ( $i = 0; $i < 3; $i++ )
				{
					$pastdate = $past->format('Y-m-d');
					$pasttime = $past->format('H:i:s');
					$statement = "
						SELECT	field, game, status
						FROM	game_slots_{$reservationDetails['arena']}
						WHERE	date = '{$pastdate}' AND 
								time = '{$pasttime}' AND
								field= {$field};";
					$rowSlot = $database->fetchRow($statement);
												
					if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'E' )
						$statement = "
							UPDATE	game_slots_{$reservationDetails['arena']}
							SET		game = 'N',
									status = 'E'
							WHERE	date = '{$pastdate}' AND 
									time = '{$pasttime}' AND
									field= {$field};";
					else if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'E_O' )
						$statement = "
							UPDATE	game_slots_{$reservationDetails['arena']}
							SET		game = 'D',
									status = 'O'
							WHERE	date = '{$pastdate}' AND 
									time = '{$pasttime}' AND
									field= {$field};";
					else if ( $rowSlot['game'] == 'U' )
						;
					else
						$database->errorManagement("reserveRelatedFunction.php //", 'Impossible Path', "{$rowSlot['game']}, {$rowSlot['status']}, $pastdate, $pasttime, {$field}");
													
					$database->runQuery($statement);//var_dump($statement);
											
					$past->sub($interval);
				}
					
				for ( $i = 0; $i < 3; $i++ )
				{
					$futuredate = $future->format('Y-m-d');
					$futuretime = $future->format('H:i:s');
					$statement = "
						SELECT	field, game, status
						FROM	game_slots_{$reservationDetails['arena']}
						WHERE	date = '{$futuredate}' AND 
								time = '{$futuretime}' AND
								field= {$field};";
					$rowSlot = $database->fetchRow($statement);
					
					if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'O' )
						$statement = "
							UPDATE	game_slots_{$reservationDetails['arena']}
							SET		game = 'N',
									status = 'E'
							WHERE	date = '{$futuredate}' AND 
									time = '{$futuretime}' AND
									field= {$field};";
					else if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'E_O' )
						$statement = "
							UPDATE	game_slots_{$reservationDetails['arena']}
							SET		game = 'D',
									status = 'E'
							WHERE	date = '{$futuredate}' AND 
									time = '{$futuretime}' AND
									field= {$field};";
					else if ( $rowSlot['game'] == 'U' )
						;
					else
						$database->errorManagement("reserveRelatedFunction.php //", 'Impossible Path', "{$rowSlot['game']}, {$rowSlot['status']}, $pastdate, $pasttime, {$field}");
							
					$database->runQuery($statement);//var_dump($statement);
						
					$future->add($interval);
				}
			}
		}
		else if ( $reservationDetails['gameType'] == 'CHALLENGE' )
		{
			$statement = "
				UPDATE	game_slots_{$reservationDetails['arena']}
				SET		game = 'N',
						status = 'E'
				WHERE	date = '{$reservationDetails['date']}' AND 
						time = '{$reservationDetails['time']}' AND
						field= {$reservationDetails['field']};";
			$database->runQuery($statement);
		}
		
		// release lock
		$statement = "SELECT RELEASE_LOCK('futsalti_{$reservationDetails['arena']}');";
		$lock = $database->fetchCell($statement);
		if ( $database->getErrorStatus() ) 
		{		
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "Lock wasn't released."
						);
			echo json_encode($response);
			exit();
		}
		
		$statement = "
			DELETE 
			FROM	reservations
			WHERE	id = {$reservationID};";
		$database->runQuery($statement);
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "Reservation slots updated but actual reservation still exists."
						);
						
			$database->__destruct(); unset($database);
			echo json_encode($response);
			exit();
		}
		
		$statement = "
				SELECT	email
				FROM	customers
				WHERE	phone = '{$reservationDetails['customer']}' OR
						phone = '{$reservationDetails['opponent']}';";
		$emailSet = $database->fetchSet($statement);
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "Emails weren't sent."
						);
			
			$database->__destruct(); unset($database);
			echo json_encode($response);
			exit();
		}
			
		$dateModF = dateFormat($reservationDetails['date']);
		
		foreach ( $emailSet as $emailRow )
		{
			$email = trim($emailRow['email']);
			$desc = 'CANCEL_M'; //( $reservationDetails['gameType'] == 'MATCH' ) ? 'CANCEL_M' : 'CANCEL_C';
			if ( !sendEmail($reservationDetails['name'], $dateModF, $reservationDetails['time'], $reservationDetails['fieldSize'], $reservationDetails['gameType'], $desc, $email) && !empty($email) )
			{
				$body = print_r($reservationDetails, true);
				$database->errorManagement("cancelBooking.php // by original\n{$body}", "E-Mail wasn't sent as should", $desc.', '.$email);
			}
		}
		
		$response = array(
						'state' => 'success',
						'title' => '<u>Futsal-Time</u>',
						'message' => "The reservation was succesfully cancelled.",
						'customerPhone' => $reservationDetails['customer'],
						'opponentPhone' => $reservationDetails['opponent'],
						'customerName' => $reservationDetails['customerN'],
						'opponentName' => $reservationDetails['opponentN'],
						'date' => $reservationDetails['date'],
						'time' => $reservationDetails['time'],
						'gameType' => $reservationDetails['gameType'],
						'gameSize' => $reservationDetails['fieldSize'],
						'fieldId' => $reservationDetails['field'],
						'accept' => $reservationDetails['accept']
					);
					
		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
?>