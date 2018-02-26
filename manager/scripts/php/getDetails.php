<?php
	include_once(dirname(__FILE__)."/levelcontrol.php");
	include_once(dirname(__FILE__)."/databaseClass.php");
	
	$date = $_POST['date']; 
	$modDate = substr($date, 6, 4).'-'.substr($date, 3, 2).'-'.substr($date, 0, 2); //echo $date;exit();
	$time = $_POST['time'];
	$field = $_POST['field'];
	$status = $_POST['status'];
	$nick = $_SESSION['nick'];
	$arenaId = $_SESSION['arenaID'];
	
	$res = '';
	$p1 = '';
	$p2 = '';
	
	$database = new MyDBManager();
	
	$statement = "SELECT DISTINCT fieldSize FROM fields_{$nick} WHERE type='D' ORDER BY fieldSize ASC;";
	$doubles = $database->fetchSet($statement);
	if ( $database->getErrorStatus() )
	{
		$response = array('state' => 'fail');
		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
	}
			
	$statement = "
		SELECT	* 
		FROM	reservations
		WHERE	arena= '{$nick}' AND 
				date = '{$modDate}' AND
				time = '{$time}' AND
				field= '{$field}';";
	$res = $database->fetchRow($statement);	
	if ( $database->getErrorStatus() )
	{
		$response = array('state' => 'fail');
		$database->__destruct(); unset($database);
		echo json_encode($response);
		exit();
	}
	
	if ( empty($res) )
	{
		$statement = "
			SELECT fieldSize FROM fields_{$nick} WHERE fieldId= '{$field}';";
		$fs = $database->fetchCell($statement);	
		$response = array(
						'state' => 'success',
						'id' => '',
						'gameType' => '',
						'fieldSize' => $fs,
						'customer' => '',
						'opponent' => '',
						'player1' => '',
						'email1' => '',
						'player2' => '',
						'email2' => '',
						'arenaId' => $arenaId
					);
	}
	else
	{
		$statement = "
			SELECT	name, email 
			FROM	customers
			WHERE	phone = '{$res['customer']}';";
		$p1 = $database->fetchRow($statement);
		
		if ( !empty($res['opponent']) )
		{
			$statement = "
				SELECT	name, email 
				FROM	customers
				WHERE	phone = '{$res['opponent']}';";
			$p2 = $database->fetchRow($statement);
		}
		else
		{
			$p2['name'] = '';
			$p2['email'] = '';
		}
		
		$response = array(
						'state' => 'success',
						'id' => $res['id'],
						'gameType' => $res['gameType'],
						'fieldSize' => $res['fieldSize'],
						'customer' => $res['customer'],
						'opponent' => $res['opponent'],
						'player1' => $p1['name'],
						'email1' => $p1['email'],
						'player2' => $p2['name'],
						'email2' => $p2['email'],
						'arenaId' => $arenaId
					);
	}
	
	$html = "
<div class='reservation_box' id='reservation_box'>
	<div id='reservation_form'>
		<fieldset><legend>Reservation Form for <b><i><span id='dateTime'>{$date} @ {$time}<span></i></b> </legend>
		<div style='margin-left:42px;'>
			<div id='customer_details' style='float:left; margin-right:50px;'>
				<div class='short_explanation'>Customer Details</div>
				
				<div class='container'>
					<label for='customerPhone' >Customer Contact Phone*: </label><br/>";
	if ( $status > 0 ) //some kind of reservation
		$html .= "
					<input type='text' name='customerPhone' id='customerPhone' value='{$response['customer']}' maxlength='50' readonly='readonly' /><br/>";
	else
		$html .= "
					<input type='text' name='customerPhone' id='customerPhone' value='' maxlength='50' onkeyup=\"livesearch(this, 'customer', event, {$response['arenaId']})\" /><br/>";
	$html .= "
					<span id='customer_phone_errorloc' class='error'></span>
					<div id='livesearch_results1' style='position:absolute;'></div>
				</div>
				
				<div class='container'>
					<label for='customerName' >Customer Name: </label><br/>";
	if ( $status > 0 ) //some kind of reservation
		$html .= "
					<input type='text' name='customerName' id='customerName' value='{$response['player1']}' maxlength='50' readonly='readonly' /><br/>";
	else
		$html .= "
					<input type='text' name='customerName' id='customerName' value='' maxlength='50'/><br/>";
	$html .= "
				</div>
				
				<div class='container'>
					<label for='customerEmail' >Customer Email Address: </label><br/>";
	if ( $status > 0 ) //some kind of reservation
		$html .= "
					<input type='text' name='customerEmail' id='customerEmail' value='{$response['email1']}' maxlength='50' readonly='readonly' /><br/>";
	else
		$html .= "
					<input type='text' name='customerEmail' id='customerEmail' value='' maxlength='50' readonly='readonly'/><br/>";
	$html .= "
					<span id='customer_email_errorloc' class='error'></span>
				</div>
				
				<div class='short_explanation'>Game Details</div>
				
				<div class='container'>
					<label for='gameField' >Type of Game*:</label><br/>
					
					<table id='game_type_options'>
						<tr>";
	if ( $status > 0 ) 
	{//some kind of reservation
		if ( $response['gameType'] == 'MATCH' || $response['gameType'] == 'MATCH_C' ) 
		{
			$html .= "
							<td id='game_type1_cont' width='50%'><input type='radio' id='game_type1' name='gameType' checked='checked' value='MATCH'/> Match</td>"; 
		}
		else if ( $response['gameType'] == 'CHALLENGE' ) 
		{
			$html .= "
							<td id='game_type2_cont' width='50%'><input type='radio' id='game_type2' name='gameType' checked='checked' value='CHALLENGE'/> Challenge</td>";
		}
		else 
		{
			$html .= "
							<td>ERROR</td>";
		}
	}
	else 
	{
		$html .= "
							<td id='game_type1_cont' width='50%'><input type='radio' id='game_type1' name='gameType' checked='checked' value='MATCH'/> Match</td>
							<td id='game_type2_cont' width='50%'><input type='radio' id='game_type2' name='gameType' value='CHALLENGE'/> Challenge</td>";
	}
	
	$html .= "
						</tr>
					</table>
					
					<span id='gameType_errorloc' class='error'></span>
				</div>
				
				<div class='container'>
					<label for='gameSize' >Size of Game*:</label><br/>
					
					<table id='game_size_options'>
						<tr>";
	if ( $status > 0 ) 
	{//some kind of reservation
		if ( $response['fieldSize'] == '5X5' || $response['fieldSize'] == '6X6' ) 
		{
			$html .= "
							<td id='game_size1_cont' width='50%'><input type='radio' id='game_size1' name='gameSize' checked='checked' value='{$response['fieldSize']}'/> {$response['fieldSize']} </td>"; 
		}
		else if ( $response['fieldSize'] == '7X7' || $response['fieldSize'] == '8X8' || $response['fieldSize'] == '9X9' || $response['fieldSize'] == '10X10' ) 
		{
			$html .= "
							<td id='game_size2_cont' width='50%'><input type='radio' id='game_size2' name='gameSize' checked='checked' value='{$response['fieldSize']}'/> {$response['fieldSize']} </td>"; 
		}
		else 
		{
			$html .= "
							<td>ERROR</td>";
		}
	}
	else 
	{
		$html .= "
							<td id='game_size1_cont' ><input type='radio' id='game_size1' name='gameSize' checked='checked' value='{$response['fieldSize']}'/> {$response['fieldSize']}</td>"; 
		$j = 2;
		foreach ( $doubles as $d )
		{//width
			$html .= "
							<td id='game_size{$j}_cont' ><input type='radio' id='game_size{$j}' name='gameSize' value='{$d['fieldSize']}'/> {$d['fieldSize']}</td>"; 
			$j++;
		}
	}
	$html .= "
						</tr>
					</table>
					
					<span id='gameSize_errorloc' class='error'></span>
				</div>	
			</div>
			
			<div id='opponent_details' style='float:left'>
			<div class='short_explanation'>Opponent Details</div>
				<div class='container'>
					<label for='opponentPhone' >Opponent Contact Phone*: </label><br/>";
	if ( $status <= 1 )
		$html .= "
					<input type='text' name='opponentPhone' id='opponentPhone' value='{$response['opponent']}' maxlength='50' readonly='readonly' /><br/>";
	else 
		$html .= "
					<input type='text' name='opponentPhone' id='opponentPhone' value='' maxlength='50' onkeydown=\"livesearch(this, 'opponent', event, {$response['arenaId']})\" /><br/>";
	$html .= "
					<span id='opponent_phone_errorloc' class='error'></span>
					
					<div id='livesearch_results2' style='position:absolute;'></div>
				</div>
				
				<div class='container'>
					<label for='opponentName' >Opponent Name: </label><br/>";
	if ( $status <= 1 ) //some kind of reservation
		$html .= "
					<input type='text' name='opponentName' id='opponentName' value='{$response['player2']}' maxlength='50' readonly='readonly' /><br/>";
	else 
		$html .= "
					<input type='text' name='opponentName' id='opponentName' value='' maxlength='50' /><br/>";
	$html .= "
				</div>
				
				<div class='container'>
					<label for='opponentEmail' >Opponent Email Address: </label><br/>";
	if ( $status <= 1 ) 
		$html .= "
					<input type='text' name='opponentEmail' id='opponentEmail' value='{$response['email2']}' maxlength='50' readonly='readonly' /><br/>";
	else 
		$html .= "
					<input type='text' name='opponentEmail' id='opponentEmail' value='' maxlength='50' readonly='readonly'/><br/>";
	$html .= "
					<span id='opponent_email_errorloc' class='error'></span>
				</div>
				
				<div class='container'>";
	if ( $status == 2 ) 
	{
		$html .= "
					<input type='button' id='reservationSubmit' name='submitR' value='Reserve' style='float:right' onclick=\"reserve('{$date}', '{$time}', 1, {$field}, {$_SESSION['smsM']}, {$_SESSION['smsC']})\"/>";
		$html .= "
					<input type='button' id='cancellationSubmit' name='submitC' value='Cancel' style='float:right' onclick=\"cancel('{$response['id']}', {$_SESSION['smsM']}, {$_SESSION['smsC']})\"/>";
	}
	else if ( $status == 1 ) 
	{
		$html .= "
					<input type='button' id='cancellationSubmit' name='submitC' value='Cancel' style='float:right' onclick=\"cancel('{$response['id']}', {$_SESSION['smsM']}, {$_SESSION['smsC']})\"/>";
	}
	else if ( $status == 0 ) 
	{
		$html .= "
					<input type='button' id='reservationSubmit' name='submitR' value='Reserve' style='float:right' onclick=\"reserve('{$date}', '{$time}', 0, {$field}, {$_SESSION['smsM']}, {$_SESSION['smsC']})\"/>";
	}
	$html .= "
				</div>
			</div>
		</div>
	</div>
</div>";
	
	$response['html'] = $html;
	
	$database->__destruct(); unset($database);
	echo json_encode($response);
	exit();
?>