<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/misc.php");
		
	function reservationFinalizedMatch($database, $arenaID, $date, $time, $type, $duration, $customer, $opponent, $fieldSize, $userEmail, $fieldId)
	{
		$dateModF = dateFormat($date);	// common format of date for use in emails and sms
		$fieldBooked;
		$time = $time.':00';
		
		$statement = "SELECT name, nick FROM arenas WHERE id={$arenaID};";
		$arenaDetails = $database->fetchRow($statement);
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);
			return $response;
		}
		$arenaNick = $arenaDetails['nick'];
		$arenaName = $arenaDetails['name'];
		
		$statement = "SELECT * FROM fields_{$arenaNick} WHERE type='D';";
		$fieldInfo = $database->fetchSet($statement);
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);
		}
		$doubles = array();
		$sizes = array();
		foreach ( $fieldInfo as $doubleField )
		{
			$doubles[$doubleField['fieldId']] = explode(',', $doubleField['containing']);
			$sizes[$doubleField['fieldId']] = $doubleField['fieldSize'];
		}
		unset($fieldInfo);
		
		// GET LOCK
		$statement = "SELECT GET_LOCK('futsalti_{$arenaNick}',10);";
		$lock = $database->fetchCell($statement);
		if ( $database->getErrorStatus() ) 
		{		
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);
			return $response;
		}
		
		//lck could not be acquired, exit script
		if ( $lock == 0 )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "Database server was busy.<br /><br />Sorry for the inconvenience. Try again."
						);
			return $response;
		}
			
		if ( $fieldSize == '5X5' || $fieldSize == '6X6' )
		{
			if ( $type == 'MATCH' )
			{
				$statement = "
					SELECT	field, game, status
					FROM	game_slots_{$arenaNick}
					INNER JOIN fields_{$arenaNick} ON field = fieldId
					WHERE	date = '{$date}' AND 
							time = '{$time}' AND
							fieldSize = '{$fieldSize}' AND
							( game = 'N' OR game = 'C') AND
							status = 'E';";
				$slotSet = $database->fetchSet($statement);
				if ( $database->getErrorStatus() ) 
				{		
					$response = array(
									'state' => 'fail',
									'title' => '<u>Futsal-Time</u>',
									'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
								);
					return $response;
				}
				
				if ( count($slotSet) == 0 )
				{		
					$response = array(
									'state' => 'fail',
									'title' => '<u>Futsal-Time</u>',
									'message' => "No available slots were found. You were probably outrunned. Try some other slot.<br />"
								);
					return $response;
				}
				
				$slotFound = null;
				// first try - no overwrite double fields
				foreach ( $slotSet as $slot )
				{
					$isDouble = false;
					foreach ( $doubles as $double )
						$isDouble = in_array($slot['field'], $double);
					if ( !$isDouble )
					{
						$slotFound = $slot;
						break;
					}
				}
				if ( $slotFound == null )
				{
					$slotFound = $slotSet[0];
				}				
				$fieldBooked = $slotFound['field'];
				
				$past = DateTime::createFromFormat('Y-m-d H:i:s', $date.' '.$time);//var_dump($past);
				$future = DateTime::createFromFormat('Y-m-d H:i:s', $date.' '.$time);//var_dump($future);
				$interval = new DateInterval("PT15M");
				$future->add($interval);
				$past->sub($interval);

				$statement = "
					UPDATE	game_slots_{$arenaNick}
					SET		game = 'MS',
							status = 'O'
					WHERE	date = '{$date}' AND 
							time = '{$time}' AND
							field= {$slotFound['field']};";
				$database->runQuery($statement);
				
				for ( $i = 0; $i < 3; $i++ )
				{
					$pastdate = $past->format('Y-m-d');
					$pasttime = $past->format('H:i:s');
					$statement = "
						SELECT	field, game, status
						FROM	game_slots_{$arenaNick}
						WHERE	date = '{$pastdate}' AND 
								time = '{$pasttime}' AND
								field= {$slotFound['field']};";
					$rowSlot = $database->fetchRow($statement);
					
					if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'O' )
						$statement = "
							UPDATE	game_slots_{$arenaNick}
							SET		game = 'D',
									status = 'E_O'
							WHERE	date = '{$pastdate}' AND 
									time = '{$pasttime}' AND
									field= {$slotFound['field']};";
					else if ( $rowSlot['game'] == 'N' || $rowSlot['game'] == 'C' )
						$statement = "
							UPDATE	game_slots_{$arenaNick}
							SET		game = 'D',
									status = 'E'
							WHERE	date = '{$pastdate}' AND 
									time = '{$pasttime}' AND
									field= {$slotFound['field']};";
					else if ( $rowSlot['game'] == 'U' )
						;
					else
						$database->errorManagement("reserveRelatedFunction.php //", 'Impossible Path', "{$rowSlot['game']}, {$rowSlot['status']}, $pastdate, $pasttime, {$slotFound['field']}");
					
					$database->runQuery($statement);
					
					$past->sub($interval);
				}
				
				for ( $i = 0; $i < 3; $i++ )
				{
					$futuredate = $future->format('Y-m-d');
					$futuretime = $future->format('H:i:s');
					$statement = "
						SELECT	field, game, status
						FROM	game_slots_{$arenaNick}
						WHERE	date = '{$futuredate}' AND 
								time = '{$futuretime}' AND
								field= {$slotFound['field']};";
					$rowSlot = $database->fetchRow($statement);
					
					if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'E' )
						$statement = "
							UPDATE	game_slots_{$arenaNick}
							SET		game = 'D',
									status = 'E_O'
							WHERE	date = '{$futuredate}' AND 
									time = '{$futuretime}' AND
									field= {$slotFound['field']};";
					else if ( $rowSlot['game'] == 'N' || $rowSlot['game'] == 'C' )
						$statement = "
							UPDATE	game_slots_{$arenaNick}
							SET		game = 'D',
									status = 'O'
							WHERE	date = '{$futuredate}' AND 
									time = '{$futuretime}' AND
									field= {$slotFound['field']};";
					else if ( $rowSlot['game'] == 'U' )
						;
					else
						$database->errorManagement("reserveRelatedFunction.php //", 'Impossible Path', "{$rowSlot['game']}, {$rowSlot['status']}, $pastdate, $pasttime, {$slotFound['field']}");
					
					$database->runQuery($statement);
					
					$future->add($interval);
				}
			}
			else if ( $type == 'CHALLENGE' )
			{
				$statement = "
					SELECT	field, game, status
					FROM	game_slots_{$arenaNick}
					INNER JOIN fields_{$arenaNick} ON field = fieldId
					WHERE	date = '{$date}' AND 
							time = '{$time}' AND
							fieldSize = '{$fieldSize}' AND
							game = 'N' AND
							status = 'E';";
				$slotSet = $database->fetchSet($statement);			
				if ( $database->getErrorStatus() ) 
				{		
					$response = array(
									'state' => 'fail',
									'title' => '<u>Futsal-Time</u>',
									'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
								);
					return $response;
				}
				
				if ( count($slotSet) == 0 )
				{		
					$response = array(
									'state' => 'fail',
									'title' => '<u>Futsal-Time</u>',
									'message' => "No available slots were found. You were probably outrunned. Try some other slot.<br />"
								);
					return $response;
				}
				
				$slotFound = null;
				foreach ( $slotSet as $slot )
				{
					$isDouble = false;
					foreach ( $doubles as $double )
						$isDouble = in_array($slot['field'], $double);
					if ( !$isDouble )
					{
						$slotFound = $slot;
						break;
					}
				}
				if ( $slotFound == null )
				{
					$slotFound = $slotSet[0];
				}
				$fieldBooked = $slotFound['field'];
				
				$statement = "
					UPDATE	game_slots_{$arenaNick}
					SET		game = 'C',
							status = 'E'
					WHERE	date = '{$date}' AND 
							time = '{$time}' AND
							field= {$slotFound['field']};";
				$database->runQuery($statement);
			}
		}
		else if ( $fieldSize == '7X7' || $fieldSize == '8X8' || $fieldSize == '9X9' || $fieldSize == '10X10' )
		{
			$gameMode = ''; if ( $fieldSize == '7X7' || $fieldSize == '8X8' ) $gameMode = 'MH'; else $gameMode = 'MD';
			
			if ( $type == 'MATCH' )
			{
				$statement = "
					SELECT	field, game, status
					FROM	game_slots_{$arenaNick}
					WHERE	date = '{$date}' AND 
							time = '{$time}' AND
							( game = 'N' OR game = 'C') AND
							status = 'E';";
				$slotSet = $database->fetchSet($statement);
				if ( $database->getErrorStatus() ) 
				{		
					$response = array(
									'state' => 'fail',
									'title' => '<u>Futsal-Time</u>',
									'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
								);
					return $response;
				}
				
				$freeDouble = null;
				// first try - no overwrite of challenge
				foreach ( $doubles as $k => $double )
				{
					if ( $sizes[$k] != $fieldSize ) continue;
					$df = count($double);
					foreach ( $slotSet as $slot )
					{
						if ( in_array($slot['field'], $double) && $slot['game'] == 'N' )
							$df--;
					}
					if ( $df == 0 )
					{
						$freeDouble = $double;
						break;
					}
				}
				// second try - overwrite challenge
				if ( $freeDouble == null )
				{
					foreach ( $doubles as $k => $double )
					{
						if ( $sizes[$k] != $fieldSize ) continue;
						$df = count($double);
						foreach ( $slotSet as $slot )
						{
							if ( in_array($slot['field'], $double) )
								$df--;
						}
						if ( $df == 0 )
						{
							$freeDouble = $double;
							break;
						}
					}	
				}
				if ( $freeDouble == null )
				{		
					$response = array(
									'state' => 'fail',
									'title' => '<u>Futsal-Time</u>',
									'message' => "No available slots were found. You were probably outrunned. Try some other slot.<br />"
								);
					return $response;
				}
				$fieldBooked = array_search($freeDouble, $doubles); //var_dump($fieldBooked);
				foreach ( $freeDouble as $field )
				{
					$past = DateTime::createFromFormat('Y-m-d H:i:s', $date.' '.$time);
					$future = DateTime::createFromFormat('Y-m-d H:i:s', $date.' '.$time);
					$interval = new DateInterval("PT15M");
					$future->add($interval);
					$past->sub($interval);//var_dump($field);
					$statement = "
						UPDATE	game_slots_{$arenaNick}
						SET		game = '{$gameMode}',
								status = 'O'
						WHERE	date = '{$date}' AND 
								time = '{$time}' AND
								field= {$field};";
					$database->runQuery($statement);//var_dump($statement);
									
					for ( $i = 0; $i < 3; $i++ )
					{
						$pastdate = $past->format('Y-m-d');
						$pasttime = $past->format('H:i:s');
						$statement = "
							SELECT	field, game, status
							FROM	game_slots_{$arenaNick}
							WHERE	date = '{$pastdate}' AND 
									time = '{$pasttime}' AND
									field= {$field};";
						$rowSlot = $database->fetchRow($statement);
												
						if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'O' )
							$statement = "
								UPDATE	game_slots_{$arenaNick}
								SET		game = 'D',
										status = 'E_O'
								WHERE	date = '{$pastdate}' AND 
										time = '{$pasttime}' AND
										field= {$field};";
						else if ( $rowSlot['game'] == 'N' || $rowSlot['game'] == 'C' )
							$statement = "
								UPDATE	game_slots_{$arenaNick}
								SET		game = 'D',
										status = 'E'
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
							FROM	game_slots_{$arenaNick}
							WHERE	date = '{$futuredate}' AND 
									time = '{$futuretime}' AND
									field= {$field};";
						$rowSlot = $database->fetchRow($statement);
						
						if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'E' )
							$statement = "
								UPDATE	game_slots_{$arenaNick}
								SET		game = 'D',
										status = 'E_O'
								WHERE	date = '{$futuredate}' AND 
										time = '{$futuretime}' AND
										field= {$field};";
						else if ( $rowSlot['game'] == 'N' || $rowSlot['game'] == 'C' )
							$statement = "
								UPDATE	game_slots_{$arenaNick}
								SET		game = 'D',
										status = 'O'
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
		}			
		
		//Search for pending challenges in affected slot-space and drop where no more free fields
		manageChallenges($database, $arenaNick, $date, $time, $fieldBooked, $fieldSize);
			
		// release lock
		$statement = "SELECT RELEASE_LOCK('futsalti_{$arenaNick}');";
		$lock = $database->fetchCell($statement);
		if ( $database->getErrorStatus() ) 
		{		
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);
			return $response;
		}
			
		$statement = "
			INSERT INTO reservations(arena, date, time, field, gameType, fieldSize, customer, opponent )
			VALUES ('$arenaNick', '$date', '$time', $fieldBooked, '$type', '$fieldSize', '$customer', '$opponent');";
		$database->runQuery($statement);
		$lastID = $database->getLastInsertId();
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);	
			return $response;
		}
		
		$statement = "
			SELECT	customer, opponent, email
			FROM	reservations 
			INNER JOIN customers ON ( customer = phone OR opponent = phone ) 
			WHERE id={$lastID};";
		$contactDetails = $database->fetchSet($statement);
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);
			return $response;
		}
					
		$phones = array();
		$emails = array();
		foreach ( $contactDetails as $row )
		{
			$phones[0] = $row['customer'];
			$phones[1] = $row['opponent'];
			$emails[] = $row['email'];
		}
		
		$desc = ( $type == 'MATCH' ) ? $type : $type.'_IS';
		foreach ( $emails as $email)
		{
			if ( sendEmail($arenaName, $dateModF, $time, $fieldSize, $type, $desc, $email) )
				$response = array(
								'state' => 'success',
								'title' => '<u>Futsal-Time</u>',
								'message' => "Your reservation was finalized. An email has been sent with the details of your match.<br /><br /></i>Date : $dateModF<br/ >Time : $time<br />Type : $type<br />Dura : $duration hour(s)</i>"
							);
			else
				$response = array(
								'state' => 'success',
								'title' => '<u>Futsal-Time</u>',
								'message' => "Your reservation was finalized but an email could not be sent. If you like you can confirm the match through the members page.<br /><br /></i>Date : $dateModF<br/ >Time : $time<br />Type : $type<br />Dura : $duration hour(s)</i>"
							);
		}
		
		if ( $type == 'CHALLENGE' ) notifyChallenges($database, $arenaName, $dateModF, $time, $fieldSize, $type, $desc);
		
		return $response;
	}
		
	function reservationFinalizedChallenge($database, $arenaID, $date, $time, $type, $duration, $customer, $opponent, $fieldSize, $userEmail, $fieldId)
	{
		$dateModF = dateFormat($date);	// common format of date for use in emails and sms
		$fieldBooked;
		$time = $time.':00';
		
		$statement = "SELECT name, nick, smsCustomer FROM arenas INNER JOIN arenaSettings ON arenas.id = arenaSettings.id WHERE arenas.id={$arenaID};";
		$arenaDetails = $database->fetchRow($statement);
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);
			return $response;
		}
		$arenaNick = $arenaDetails['nick'];
		$arenaName = $arenaDetails['name'];
		$sendSMS2C = $arenaDetails['smsCustomer'];
		unset($arenaDetails);
		
		$statement = "SELECT * FROM fields_{$arenaNick} WHERE type='D';";
		$fieldInfo = $database->fetchSet($statement);
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);
		}
		$doubles = array();
		$sizes = array();
		foreach ( $fieldInfo as $doubleField )
		{
			$doubles[$doubleField['fieldId']] = explode(',', $doubleField['containing']);
			$sizes[$doubleField['fieldId']] = $doubleField['fieldSize'];
		}
		unset($fieldInfo);
		
		// GET LOCK
		$statement = "SELECT GET_LOCK('futsalti_{$arenaNick}',10);";
		$lock = $database->fetchCell($statement);
		if ( $database->getErrorStatus() ) 
		{		
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);
			return $response;
		}
		
		//lck could not be acquired, exit script
		if ( $lock == 0 )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "Database server was busy.<br /><br />Sorry for the inconvenience. Try again."
						);
			return $response;
		}
		
		$statement = "
			SELECT	field, game, status
			FROM	game_slots_{$arenaNick}
			INNER JOIN fields_{$arenaNick} ON field = fieldId
			WHERE	date = '{$date}' AND 
					time = '{$time}' AND
					fieldSize = '{$fieldSize}' AND
					game = 'C' AND
					status = 'E';";
		$slotSet = $database->fetchSet($statement);
		if ( $database->getErrorStatus() ) 
		{		
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);
			return $response;
		}
		
		if ( count($slotSet) == 0 )
		{		
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "No available slots were found. You were probably too late. Try some other slot.<br />"
						);
			return $response;
		}
			
		$slotFound = $slotSet[0];
		$fieldBooked = $slotFound['field'];
		
		$past = DateTime::createFromFormat('Y-m-d H:i:s', $date.' '.$time);
		$future = DateTime::createFromFormat('Y-m-d H:i:s', $date.' '.$time);
		$interval = new DateInterval("PT15M");
		$future->add($interval);
		$past->sub($interval);

		$statement = "
			UPDATE	game_slots_{$arenaNick}
			SET		game = 'MC',
					status = 'O'
			WHERE	date = '{$date}' AND 
					time = '{$time}' AND
					field= {$slotFound['field']};";
		$database->runQuery($statement);
		
		for ( $i = 0; $i < 3; $i++ )
		{
			$pastdate = $past->format('Y-m-d');
			$pasttime = $past->format('H:i:s');
			$statement = "
				SELECT	field, game, status
				FROM	game_slots_{$arenaNick}
				WHERE	date = '{$pastdate}' AND 
						time = '{$pasttime}' AND
						field= {$slotFound['field']};";
			$rowSlot = $database->fetchRow($statement);
			
			if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'O' )
				$statement = "
					UPDATE	game_slots_{$arenaNick}
					SET		game = 'D',
							status = 'E_O'
					WHERE	date = '{$pastdate}' AND 
							time = '{$pasttime}' AND
							field= {$slotFound['field']};";
			else if ( $rowSlot['game'] == 'N' || $rowSlot['game'] == 'C' )
				$statement = "
					UPDATE	game_slots_{$arenaNick}
					SET		game = 'D',
							status = 'E'
					WHERE	date = '{$pastdate}' AND 
							time = '{$pasttime}' AND
							field= {$slotFound['field']};";
			else if ( $rowSlot['game'] == 'U' )
				;
			else
				$database->errorManagement("reserveRelatedFunction.php //", 'Impossible Path', "{$rowSlot['game']}, {$rowSlot['status']}, $pastdate, $pasttime, {$slotFound['field']}");
			
			$database->runQuery($statement);
			
			$past->sub($interval);
		}
		
		for ( $i = 0; $i < 3; $i++ )
		{
			$futuredate = $future->format('Y-m-d');
			$futuretime = $future->format('H:i:s');
			$statement = "
				SELECT	field, game, status
				FROM	game_slots_{$arenaNick}
				WHERE	date = '{$futuredate}' AND 
						time = '{$futuretime}' AND
						field= {$slotFound['field']};";
			$rowSlot = $database->fetchRow($statement);
			
			if ( $rowSlot['game'] == 'D' && $rowSlot['status'] == 'E' )
				$statement = "
					UPDATE	game_slots_{$arenaNick}
					SET		game = 'D',
							status = 'E_O'
					WHERE	date = '{$futuredate}' AND 
							time = '{$futuretime}' AND
							field= {$slotFound['field']};";
			else if ( $rowSlot['game'] == 'N' || $rowSlot['game'] == 'C' )
				$statement = "
					UPDATE	game_slots_{$arenaNick}
					SET		game = 'D',
							status = 'O'
					WHERE	date = '{$futuredate}' AND 
							time = '{$futuretime}' AND
							field= {$slotFound['field']};";
			else if ( $rowSlot['game'] == 'U' )
				;
			else
				$database->errorManagement("reserveRelatedFunction.php //", 'Impossible Path', "{$rowSlot['game']}, {$rowSlot['status']}, $pastdate, $pasttime, {$slotFound['field']}");
			
			$database->runQuery($statement);
			
			$future->add($interval);
		}	
			
		// update reservation
		$statement = "
			SELECT	id 
			FROM	reservations
			WHERE	arena = '{$arenaNick}' AND
					date = '{$date}' AND
					time = '{$time}' AND
					field= $fieldBooked AND
					gameType = 'CHALLENGE';";
		$updateID = $database->fetchCell($statement); //var_dump($statement); //var_dump($updateID);
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);		
			return $response;
		}
					
		$statement = "
			UPDATE	reservations 
			SET		opponent = '{$customer}', 
					gameType = 'MATCH_C'
			WHERE	id = {$updateID};";
		$database->runQuery($statement);
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);	
			return $response;
		}
		
		//Search for pending challenges in affected slot-space and drop where no more free fields
		manageChallenges($database, $arenaNick, $date, $time, $fieldBooked, $fieldSize);
					
		// release lock
		$statement = "SELECT RELEASE_LOCK('futsalti_{$arenaNick}');";
		$lock = $database->fetchCell($statement);
		if ( $database->getErrorStatus() ) 
		{	
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);
			return $response;
		}
		
		$statement = "
			SELECT	customer, opponent, email
			FROM	reservations 
			INNER JOIN customers ON ( customer = phone OR opponent = phone ) 
			WHERE id={$updateID};";
		$contactDetails = $database->fetchSet($statement);
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);
			return $response;
		}
						
		$phones = array();
		$emails = array();
		foreach ( $contactDetails as $row )
		{
			$phones[0] = $row['customer'];
			$phones[1] = $row['opponent'];
			$emails[] = $row['email'];
		}
		
		if ( $sendSMS2C == '1' )
		{
			sendSMS($arenaName, $dateModF, $time, $fieldSize, $phones[0], 'Challenge was accepted');
		}
		
		$desc = 'CHALLENGE_AC';
		foreach ( $emails as $email)
		{
			if ( sendEmail($arenaName, $dateModF, $time, $fieldSize, $type, $desc, $email) )
				$response = array(
								'state' => 'success',
								'title' => '<u>Futsal-Time</u>',
								'message' => "Your reservation was finalized. An email has been sent with the details of your match.<br /><br /></i>Date : $dateModF<br/ >Time : $time<br />Type : $type<br />Dura : $duration hour(s)</i>"
							);
			else
				$response = array(
								'state' => 'success',
								'title' => '<u>Futsal-Time</u>',
								'message' => "Your reservation was finalized but an email could not be sent. If you like you can confirm the match through the members page.<br /><br /></i>Date : $dateModF<br/ >Time : $time<br />Type : $type<br />Dura : $duration hour(s)</i>"
							);
		}
		return $response;
	
	}
	
	function manageChallenges($database, $arenaNick, $date, $time, $field, $fieldSize)
	{
		$fieldMin = $field;
		$fieldMax = $field;
		
		if ( $fieldSize == '7X7' || $fieldSize == '8X8' || $fieldSize == '9X9' || $fieldSize == '10X10' )
		{
			$statement = "SELECT containing FROM fields_{$arenaNick} WHERE fieldId = $field AND type = 'D';";
			$fieldInfo = $database->fetchCell($statement);
			if ( !empty($fieldInfo) )
			{
				$fieldMin = substr($fieldInfo,0,1);
				$fieldMax = substr($fieldInfo,-1,1);
			}
		}
		
		$statement = "
			SELECT	id, date, time, field, customer, fieldSize
			FROM	reservations
			WHERE	arena = '{$arenaNick}' AND
					date = '{$date}' AND (
						time > SUBTIME('$time', '01:00') AND
						time < ADDTIME('$time', '01:00') 
					) AND ( 
						field >= $fieldMin AND 
						field <= $fieldMax
					) AND
					gameType = 'CHALLENGE';";
		$challengeSlots = $database->fetchSet($statement);
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
						);
			return $response;
		}
		
		foreach ( $challengeSlots as $ch )
		{
			$statement = "
				SELECT	id, field, game, status
				FROM	game_slots_{$arenaNick}
				INNER JOIN fields_{$arenaNick} ON field = fieldId
				WHERE	date = '{$ch['date']}' AND 
						time = '{$ch['time']}' AND
						fieldSize = '{$ch['fieldSize']}' AND
						game = 'N' AND
						status = 'E';";
			$slotSet = $database->fetchSet($statement);
			if ( $database->getErrorStatus() ) 
			{		
				$response = array(
								'state' => 'fail',
								'title' => '<u>Futsal-Time</u>',
								'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
							);
				return $response;
			}
				
			if ( count($slotSet) == 0 )
			{		
				// CANCEL CHALLENGE
				$statement = "
					DELETE	
					FROM	reservations
					WHERE	id = {$ch['id']};";
				$database->runQuery($statement);
				return ;
			}

			$slotFound = $slotSet[0];				
			$statement = "
					UPDATE	game_slots_{$arenaNick}
					SET		game = 'C',
							status = 'E'
					WHERE	date = '{$ch['date']}' AND 
							time = '{$ch['time']}' AND
							field= {$slotFound['field']};"; //change to id (equivalent)
			$database->runQuery($statement);
			
			$statement = "
					UPDATE	reservations
					SET		field = {$slotFound['field']}
					WHERE	id = {$ch['id']};";
			$database->runQuery($statement);
		}
		return ;
	}

?>
