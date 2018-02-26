<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/languages/lang.{$_POST['lang']}.php");
	include_once(dirname(__FILE__)."/reserveRelatedFunctions.php");
	include_once(dirname(__FILE__)."/misc.php");
	
	$fieldSize = $_POST['size'];
	$reqType = $_POST['reqType'];
	
	$date = $_POST['date'];
	$dateModF = dateFormat($date);
	$time = $_POST['time'];
	$arenaID = $_POST['arenaID'];
	
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$body = '';
	$headers = '';
	
	$database = new MyDBManager();
	
	if ( $reqType == 'PENDING' || $reqType == 'ACCEPT' )
	{
		$result = hasReservation($database, $lang, $phone, $arenaID, $date, $time);
		if ( $result['state']=='success' )
			$result = reservationPending($database, $lang, $arenaID, $date, $time, $reqType, $fieldSize);
		
		$database->__destruct(); unset($database);
		echo json_encode($result);
		exit();
	}
	else if ( $reqType == 'FINALIZING' )
	{
		$duration = $_POST['duration'];
		$type = $_POST['type'];
		$cost = $_POST['cost'];
		$opponent = $_POST['opponent'];
		$customer = $_POST['phone'];
		$challenges = $_POST['challeng'];
		$acceptChal = $_POST['accept'];
		
		if ( $type == 'MATCH' && $acceptChal == 0 )
			$result = reservationFinalizedMatch($database, $lang, $arenaID, $date, $time, $type, $duration, $customer, $opponent, $fieldSize, $email);
		else if ( $type == 'CHALLENGE' && $acceptChal == 0 && $challenges == 0 )
			$result = reservationFinalizedMatch($database, $lang, $arenaID, $date, $time, $type, $duration, $customer, $opponent, $fieldSize, $email);
		else if ( $type == 'CHALLENGE' && $acceptChal == 1 )
			$result = reservationFinalizedChallenge($database, $lang, $arenaID, $date, $time, $type, $duration, $customer, $opponent, $fieldSize, $email);
		else
		{
			$body = print_r($_POST, true);
			$database->errorManagement("reserve.php // Finalizing\n{$body}", 'Impossible Path', '');
			$result = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => $lang['ERROR_QUERY']
						);
		}
		
		if ( $result['state'] == 'success' )
		{
			$statement = "
				INSERT IGNORE INTO customersRep(customerPhone, arenaId )
				VALUES ('$customer', '$arenaID');";
			$database->runQuery($statement);			
		}
		
		$database->__destruct(); unset($database);
		echo json_encode($result);
		exit();
	}
	else
	{
		$body = "($reqType, $fieldSize, $groupFields, $date, $dateModF, $time, $arenaID, $email)";
		$database->errorManagement("reserve.php // N/A choice\n{$body}", 'Impossible Path', '');
		$result = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['ERROR_QUERY']
					);
					
		$database->__destruct(); unset($database);
		echo json_encode($result);
		exit();
	}
?>