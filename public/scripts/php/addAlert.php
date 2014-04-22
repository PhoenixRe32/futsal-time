<?php
	include_once(dirname(__FILE__)."/levelcontrol.php");
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/languages/lang.{$_POST['lang']}.php");
	include_once(dirname(__FILE__)."/reserveRelatedFunctions.php");
	
	$reqType = $_POST['reqType'];
	$arenaId = $_POST['arenaId'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	$fieldSize = $_POST['size'];
	
	$database = new MyDBManager();
	
	$result = reservationPending($database, $lang, $arenaId, $date, $time, $reqType, $fieldSize);
	if ( $result['state'] == 'fail_success' )
	{
		$statement = "
			INSERT IGNORE INTO customersAlerts VALUES
			(NULL, '{$_SESSION['phone']}', {$arenaId}, '{$date}', '{$time}', '{$fieldSize}');";
		$database->runQuery($statement);
		/* if ( $database->getErrorStatus() ) 
		{
			$result = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => '(adding alert) '.$lang['ERROR_QUERY']
					);
		} */
	}
	
	$database->__destruct(); unset($database);
	echo json_encode($result);
	exit();
?>