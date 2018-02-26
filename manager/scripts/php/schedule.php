<?php
	include_once(dirname(__FILE__)."/levelcontrol.php");
	include_once(dirname(__FILE__)."/databaseClass.php");

	$date = $_POST['date'];
	// $arena = $_SESSION['arenaID'];
	// $name = $_SESSION['name'];
	$nick = $_SESSION['nick'];
	$format = $_SESSION['sfM'];
	// $dateNTime = DateTime::createFromFormat('Y-m-d H:i:s', $date.' 00:00:00');
	// $interval;
	
	if ( trim($nick) == '' || trim($format) == '' )
	{
		echo 'Refresh the page. If the error consists logout and login again!';
		exit();
	}
	$html = ''; 
	
	$database = new MyDBManager();
	$statement = "SELECT COUNT(*) FROM fields_{$nick} WHERE type = 'S';";
	$numFields = $database->fetchCell($statement);
	
	switch ( $format )
	{
		case '15': 
			// $interval = new DateInterval("PT15M");
			$statement = "SELECT * FROM game_slots_{$nick} WHERE date = '{$date}' AND game != 'U';"; 
			break;
		case '30': 
			// $interval = new DateInterval("PT30M");
			$statement = "SELECT * FROM game_slots_{$nick} WHERE date = '{$date}' AND ( MINUTE(time) = 0 OR MINUTE(time) = 30 ) AND game != 'U';"; 
			break;
		case '60': 
			// $interval = new DateInterval("PT60M");
			$statement = "SELECT * FROM game_slots_{$nick} WHERE date = '{$date}' AND MINUTE(time) = 0 AND game != 'U';"; 
			break;
		default  : 
			// $interval = new DateInterval("PT15M");
			$statement = "SELECT * FROM game_slots_{$nick} WHERE date = '{$date}' AND game != 'U';"; 
			break;
	}
	
	$day = $database->fetchSet($statement);
	if ( $database->getErrorStatus() ) 
	{
		$response = "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.";
		$database->__destruct(); unset($database);
		echo $html;
		exit();
	}
	
	foreach ( $day as $slot ) 
	{
		if ( $slot['field'] == '1' )
		{
			$html .= "
				<div id='".$slot['date']."_".str_replace(':', '-', $slot['time'])."' class='fields'>
					<div style='float:left; cursor:pointer; padding-top:15px;width:42px' onclick=\"showInterest('{$date}', '{$slot['time']}')\">
						<b><i>".substr($slot['time'], 0, 5)."</i></b>
					</div>";
		}
		
		$html .= "
					<div id='".$slot['date']."_".str_replace(':', '-', $slot['time'])."_field{$slot['field']}' ";
		
		if ( $slot['game'] == 'N' && $slot['status'] == 'E' )
			$html .= "class='customScr game free'  onclick='showDetails(this, {$slot['field']}, 0);'>";
		else if ( $slot['game'] == 'D' && $slot['status'] == 'E' )
			$html .= "class='customScr game empty'  onclick='showDetails(this, {$slot['field']}, -1);'>";
		else if ( $slot['game'] == 'D' && $slot['status'] == 'O' )
			$html .= "class='customScr game occupied'  onclick='showDetails(this, {$slot['field']}, -1);'>";
		else if ( $slot['game'] == 'D' && $slot['status'] == 'E_O' )
			$html .= "class='customScr game occupied'  onclick='showDetails(this, {$slot['field']}, -1);'>";
		else if ( $slot['game'] == 'MS' && $slot['status'] == 'O' )
			$html .= "class='customScr game bookedGame'  onclick='showDetails(this, {$slot['field']}, 1);'>";
		else if ( $slot['game'] == 'MD' && $slot['status'] == 'O' )
		{
			$statement = "SELECT fieldId FROM fields_{$nick} WHERE type = 'D' AND 
							( fieldSize = '9X9' || fieldSize = '10X10' ) AND 
							containing LIKE '%{$slot['field']}%';";
			$dblField = $database->fetchCell($statement);
			$html .= "class='customScr game bookedGame10'  onclick='showDetails(this, {$dblField}, 1);'>";
		}
		else if ( $slot['game'] == 'MH' && $slot['status'] == 'O' )
		{
			$statement = "SELECT fieldId FROM fields_{$nick} WHERE type = 'D' AND 
							( fieldSize = '7X7' || fieldSize = '8X8' ) AND 
							containing LIKE '%{$slot['field']}%';";
			$dblField = $database->fetchCell($statement);
			$html .= "class='customScr game bookedGame7'  onclick='showDetails(this, {$dblField}, 1);'>";
		}
		else if ( $slot['game'] == 'MC' && $slot['status'] == 'O' )
			$html .= "class='customScr game bookedGame'  onclick='showDetails(this, {$slot['field']}, 1);'>";
		else if ( $slot['game'] == 'C' && $slot['status'] == 'E' )
			$html .= "class='customScr game challenge'  onclick='showDetails(this, {$slot['field']}, 2);'>";
		else
			$html .= "class='customScr game error'  onclick='showDetails(this, {$slot['field']}, -1);'>";
		
		$html .= "
					</div>";
		
		if ( $slot['field'] == $numFields )
		{
			$html .= "
				</div>
				
				<div id='".$slot['date']."_".str_replace(':', '-', $slot['time'])."_details' class='detailsSection' style='display:none;'>
				</div>";
		}
	}
	
	$database->__destruct(); unset($database);	
	
	echo $html;
?>