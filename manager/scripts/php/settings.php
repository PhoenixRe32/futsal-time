<?php
	include_once(dirname(__FILE__)."/levelcontrol.php");
	include_once(dirname(__FILE__)."/databaseClass.php");
	
	$mode = $_POST['mode'];
	$statement = '';
	
	if ( $mode == 'set' )
	{
		$bl = $_POST['bl'];
		$sfC = $_POST['sfC'];
		$sfM = $_POST['sfM'];
		$rf = $_POST['rf'];
		$cp = $_POST['cp'];
		$smsC = $_POST['smsC'];
		$smsM = $_POST['smsM'];
		$smsP = $_POST['smsP'];

		if ( !empty($smsP) )
		{
			$discard = array(' ', '-');
			$smsP = str_replace($discard,'',$smsP);
			if ( preg_match("/^(99|96|97)[0-9]{6}$/", $smsP) != 1 )
			{
				$response = array(
								'state' => 'fail',
								'title' => '<u>Futsal-Time</u>',
								'message' => "The mobile phone number used as the SMS contact phone was invalid. ({$smsP})"
							);
				echo json_encode($response);
				exit();
			}
			$statement = "
				UPDATE	arenaSettings
				SET		bookingLimit = {$bl},
						slotFormatClient = '{$sfC}',
						slotFormatManager = '{$sfM}',
						refreshRate = {$rf},
						phones = '{$cp}',
						smsCustomer = {$smsC},
						smsManager = {$smsM},
						smsContact = '{$smsP}'
				WHERE	id = {$_SESSION['arenaID']};";
		}
		else
		{
			$statement = "
				UPDATE	arenaSettings
				SET		bookingLimit = {$bl},
						slotFormatClient = '{$sfC}',
						slotFormatManager = '{$sfM}',
						refreshRate = {$rf},
						phones = '{$cp}',
						smsCustomer = {$smsC},
						smsManager = {$smsM},
						smsContact = NULL
				WHERE	id = {$_SESSION['arenaID']};";
			
		}
		
		$database = new MyDBManager();
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
							'message' => "Your settings have been succesfully updated.<br />"
						);
			$_SESSION['bl'] = $bl;
			$_SESSION['sfC'] = $sfC;
			$_SESSION['sfM'] = $sfM;
			$_SESSION['rr'] = $rf;
			$_SESSION['p'] = $cp;
			$_SESSION['smsC'] = $smsC;
			$_SESSION['smsM'] = $smsM;
			$_SESSION['smsP'] = $smsP;
				
			// $see = ''; foreach ( $_SESSION as $k=>$v) $see .= $k.' => '.$v."";
		}

		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
	}
	else if ( $mode == 'smsAdd' )
	{
		$s = $_POST['s'];
		$e = $_POST['e'];
		
		$database = new MyDBManager();
		
		$statement = "
			INSERT INTO	smsTimes(arena, startAt, endAt) VALUES
			({$_SESSION['arenaID']}, '{$s}', '{$e}');";
		
		$database->runQuery($statement);
		
		if ( $database->getErrorStatus() )
		{
			$response = array(
							'state' => 'fail',
							'title' => '<u>Futsal-Time</u>',
							'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br /><br />Are you sure it is not a duplicate?"
						);
		}
		else
		{
			$lastID = $database->getLastInsertId();
			$response = array(
							'state' => 'success',
							'title' => '<u>Futsal-Time</u>',
							'message' => "Your settings have been succesfully updated.<br />",
							'id' => $lastID
						);
		}

		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
	}
	else if ( $mode == 'smsDel' )
	{
		$id = $_POST['id'];
		
		$database = new MyDBManager();
		
		$statement = "
			DELETE FROM	smsTimes
			WHERE		id = {$id};";
		
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
							'message' => "Your settings have been succesfully updated.<br />"
						);
		}

		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
	}
?>