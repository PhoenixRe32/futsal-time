<?php
	include_once(dirname(__FILE__)."/levelcontrol.php");
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/userRelatedFunctions.php");
	
	$database = new MyDBManager();
	
	$newname = $_POST['name'];
	$oldPassword = $_POST['old_password'];
	$newPassword = $_POST['new_password'];
	$notifications = $_POST['notifications'];

	if(passwordMatches($database,$_SESSION['email'],$oldPassword)!=null)
	{
		$newphone=str_replace('-','',$newphone);
		updatePhone($database,$_SESSION['email'],$newname,$newPassword,$notifications,$_SESSION['phone']);
		$_SESSION['name']=$newname;
		$_SESSION['notification_challenges']=$notifications;
		
		// session_write_close();
		
		$response = array(
						'state' => 'success',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['SETTINGS_SUC']
					);
					
		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
	}
	else
	{
		$response = array(
						'state' => 'fail',
						'title' => '<u>Futsal-Time</u>',
						'message' => $lang['PSW_INC']
					);
					
		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
	}
?>