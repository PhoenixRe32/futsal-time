<?php
include_once(dirname(__FILE__)."/databaseClass.php");

$order = $_POST['o'];
$dir = $_POST['d'];
$aernaid = $_POST['a'];

$database = new MyDBManager();
$statement = "
	SELECT	name, phone, email, validated, reputation, notes
	FROM	customers
	INNER JOIN customersRep ON phone = customerPhone
	WHERE	arenaId = {$aernaid}
	ORDER BY {$order} {$dir};";
$members = $database->fetchSet($statement);	

if ( $database->getErrorStatus() )
{
	$response = array(
					'state' => 'fail',
					'title' => '<u>Futsal-Time</u>',
					'message' => "An error occured when trying to submit your query. An email has been sent with the details of the error and our team will look into it. Meanwhile we encourage you to try again.<br />"
				);
	$database->__destruct(); unset($database);
	echo json_encode($response);
	exit();
}

$html = '';
foreach ( $members as $m )
{
	$bgcolour = '';
	if ( $m['reputation'] == 'GOOD' ) $bgcolour = "style='background-color:Gold'";
	else if ( $m['reputation'] == 'BAD' ) $bgcolour = "style='background-color:FireBrick'";
	else if ( $m['reputation'] == 'NEUTRAL' ) $bgcolour = "style='background-color:Gainsboro'";
	$html .= "
			<tr id='{$m['phone']}_row' $bgcolour>";
	if ( trim($m['name']) == '')
		$html .= "
				<td id='{$m['phone']}_name' width='27%' align='left' style='cursor:pointer; border-bottom-style:none; line-height:2.5' onclick=\"updateRepName('{$m['phone']}')\">{$m['name']}</td>";
	else
		$html .= "
				<td id='{$m['phone']}_name' width='25%' align='left' style='border-bottom-style:none; line-height:2.5' >{$m['name']}</td>";
	$html .= "
				<td width='12%' align='center' style='border-bottom-style:none; line-height:2.5'>{$m['phone']}</td>
				<td width='10%' align='center' style='border-bottom-style:none; line-height:2.5'><a href='mailto:{$m['email']}'>Send</a></td>
				<td width='8%' align='center' style='border-bottom-style:none; line-height:2.5'>";
	if ( $m['validated'] == 'VALIDATED' ) $html .= "YES"; else $html .= "NO";
	$html .= "
				</td>
				<td width='12%' align='center' style='border-bottom-style:none; line-height:2.5'>";
	if ( $m['reputation'] == 'GOOD' )
		$html .= "		<select class='rep' onchange=\"updateReputation('{$m['phone']}', '{$aernaid}', this)\">
						<option selected='selected' value='GOOD'>GOOD</option>
						<option value='NEUTRAL'>NEUTRAL</option>
						<option value='BAD'>BAD</option>
					</select>";
	else if ( $m['reputation'] == 'BAD' )
		$html .= "		<select class='rep' onchange=\"updateReputation('{$m['phone']}', '{$aernaid}', this)\">
						<option value='GOOD'>GOOD</option>
						<option value='NEUTRAL'>NEUTRAL</option>
						<option selected='selected' value='BAD'>BAD</option>
					</select>";
	else 
		$html .= "		<select class='rep' onchange=\"updateReputation('{$m['phone']}', '{$aernaid}', this)\">
						<option value='GOOD'>GOOD</option>
						<option selected='selected' value='NEUTRAL'>NEUTRAL</option>
						<option value='BAD'>BAD</option>
					</select>";
	$html .= "
				</td>
				<td width='28%' align='center' style='border-bottom-style:none;'><span id='{$m['phone']}_notes'>{$m['notes']}</span><img src='./images/edit-16.png' style='cursor:pointer;' onclick=\"updateRepNotes('{$m['phone']}', '{$aernaid}')\" /></td>
				</td>
				<td width='5%' align='center' style='border-bottom-style:none;'><img src='./images/delete.jpg' style='cursor:pointer;' onclick=\"removeMember('{$m['phone']}')\" /></td>
			
			</tr>";
}			

$response = array(
					'state' => 'success',
					'title' => '<u>Futsal-Time</u>',
					'message' => $html
				);
$database->__destruct(); unset($database);
echo json_encode($response);
exit();	
?>