<?php
	include_once(dirname(__FILE__)."/levelcontrol.php");
	include_once(dirname(__FILE__)."/databaseClass.php");
	
	$n = $_POST['n'];
	$c = $_POST['c'];
	
	$currentHash = crypt($c, '$6$rounds=8888$'.$_SESSION['arenaID'].$_SESSION['name'].'$');
	$newHash = crypt($n, '$6$rounds=8888$'.$_SESSION['arenaID'].$_SESSION['name'].'$');
	
	$database = new MyDBManager();
	$statement = "
			UPDATE	managers
			SET		password = '{$newHash}'
			WHERE	arena='{$_SESSION['arenaID']}' AND name = '{$_SESSION['name']}' AND password='{$currentHash}';";
	$query = $database->runQuery($statement); //var_dump($statement);
	$affected = $query->rowCount();	
	
	if ( $database->getErrorStatus() )
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
					);
	}
	if ( $affected == 1 )
	{
		$response = array(
						'state' => 'success',
						'title' => '<u>Futsal-Time</u>',
						'message' => "Your settings have been succesfully updated.<br />"
					);
	}
	else
	{
		$response = array(
						'state' => 'success',
						'title' => '<u>Futsal-Time</u>',
						'message' => "No changes were made.<br />"
					);
	}
	
	$database->__destruct(); unset($database);
	echo json_encode($response);
	exit();
?>