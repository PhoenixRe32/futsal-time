<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	
	$customerPhone = $_POST['p'];
	$value = addslashes(htmlentities($_POST['v']));
	$database = new MyDBManager();
		
	$statement = "
		UPDATE	customers
		SET		name = '{$value}'
		WHERE	phone = '{$customerPhone}';";
		
	$database->runQuery($statement);
	
	if ( $database->getErrorStatus() )
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
					);
	}
	else
	{
		$response = array(
						'state' => 'success',
						'title' => '<u>Futsal-Time</u>',
						'message' => "The reputation has been updated.<br />"
					);
	}

	$database->__destruct(); unset($database);
	echo json_encode($response);
	exit();
?>