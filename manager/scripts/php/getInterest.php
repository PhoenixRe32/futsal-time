<?php
	include_once(dirname(__FILE__)."/levelcontrol.php");
	include_once(dirname(__FILE__)."/databaseClass.php");
	
	$date = $_POST['date']; 
	$time = $_POST['time'];
	$arena = $_SESSION['arenaID'];
	
	$database = new MyDBManager();
	
	$statement = "
		SELECT	customerPhone, fieldSize, name, email
		FROM	customersAlerts
		INNER JOIN customers ON customerPhone = phone
		WHERE	arenaId= {$arena} AND 
				date = '{$date}' AND
				time = '{$time}';";
	$res = $database->fetchSet($statement);	
	if ( empty($res) )
	{	
		$response = array(
						'state' => 'success',
						'info' => 'empty'
					);
						
		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
	}
	
	$info = '';
	foreach ( $res as $r )
	{
		$info .= "{$r['name']} [{$r['customerPhone']}] has shown interest for a {$r['fieldSize']} (<a href='mailto:{$r['email']}'>Email</a>) ";
		$info .= "<br />";
	}
	$response = array(
					'state' => 'success',
					'info' => $info
				);
					
	$database->__destruct(); unset($database);
	echo json_encode($response);
	exit();
?>