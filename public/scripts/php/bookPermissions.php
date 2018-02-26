<?php
	include_once(dirname(__FILE__)."/levelcontrol.php");
	include_once(dirname(__FILE__)."/databaseClass.php");
	
	$arenaID = $_POST['arenaID'];
	
	$database = new MyDBManager();
	
	$statement = "
		SELECT	bookingLimit, nick
		FROM	arenas
		INNER JOIN arenaSettings ON arenaSettings.id =  arenas.id
		WHERE	arenas.id = $arenaID;";
	$arenaDetails = $database->fetchRow($statement);
	if ( $database->getErrorStatus() )
	{
		$database->__destruct(); unset($database);
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['ERROR_QUERY']
					);
		echo json_encode($response);
		exit();
	}
	
	$statement = "
		SELECT	COUNT(*)
		FROM	reservations
		WHERE	(customer = '{$_SESSION['phone']}' OR opponent = '{$_SESSION['phone']}') AND 
				arena = '{$arenaDetails['nick']}' AND
				DATE_ADD(date, INTERVAL time HOUR_SECOND) >= DATE_ADD(NOW(), INTERVAL 9 HOUR);";// set interval 0 for local and 7 for host
	$numReservations = $database->fetchCell($statement);
	if ( $database->getErrorStatus() )
	{
		$database->__destruct(); unset($database);
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['ERROR_QUERY']
					);
		echo json_encode($response);
		exit();
	}
	
	$database->__destruct(); unset($database);
	
	if ( $numReservations < $arenaDetails['bookingLimit'] )
		$response = array(
						'state' => 'success',
						'title' => '<u>Futsal-Time</u>',
						'message' => "{$arenaDetails['bookingLimit']}-{$numReservations}"
					);
	else
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['BOOK_LIMIT_REACHED']
					);
		
	echo json_encode($response);
	exit();
?>