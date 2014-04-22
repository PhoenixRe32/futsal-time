<?php
include_once(dirname(__FILE__)."/scripts/php/userRelatedFunctions.php");
?>

<div style='padding-top:10px; margin:0px 30px 0px 30px;'>
<div id='bookHistoryFrame' class='member_frame' width='100%' style='min-height:325px; margin-bottom:20px;'>
	<h4 align='center'><i><?php echo $lang['BOOK_HISTORY']; ?></i></h4>
	<div id='bookingGroupArea' style='min-height:220px;'>
<?php

$database = new MyDBManager();

$bookings = fetchUserSchedule($database, $_SESSION['phone']); //var_dump($bookings);//echo count($bookings);//exit();
date_default_timezone_set($database->getTimeZone());
$database->__destruct(); unset($database);
$now = new DateTime();
$i = 0;
$j = 1;
$htmlRow = array();
$table = array();
$tfoot = "
	<tfoot>";
foreach ( $bookings as $row )
{
	$i++;
	if ( $i == 1 )
	{
		$table[$j] = "
<table id='bookingGroup{$j}' width='100%' border='0' style='padding:0px 0px 0px 30px; display:table;'>
	<thead>
		<input type='hidden' name='bookingGroupID' id='bookingGroupID' value='{$j}'/>
	</thead>	
	<tbody>";
	}
	else if ( $i % 6 == 1 )
	{
		$j++;
		$table[$j] = "
<table id='bookingGroup{$j}' width='100%' border='0' style='padding:0px 0px 0px 30px; display:none;'>
	<thead>
		<input type='hidden' name='bookingGroupID' id='bookingGroupID' value='{$j}'/>
	</thead>	
	<tbody>";
	}
	$dateMod = dateFormat($row['date']);
	$timeMod = substr($row['time'], 0, 5);
	
	$htmlRow[$i] = "
		<tr id='booking{$i}'>	
			<td >{$dateMod}</td>
			<td >{$timeMod}</td>
			<td align='center'>{$row['name']}</td>
			<td >{$row['gameType']} ({$row['fieldSize']})</td>";
	$resDateTime = new DateTime($row['date'].' '.$row['time']);
	$interval = $now->diff($resDateTime);
	if ( $resDateTime > $now )
	{
		if ( $interval->d == 0 )
		{
			if ( $interval-> h >= 1 )
			{
				$htmlRow[$i] .="
			<td><button type='button' id='cancelBooking' class='button orange' style='width:160px;' onclick=\"cancelBooking('{$row['id']}');return false;\">{$lang['CANCEL_BOOKING']}</button></td>
		</tr>";
			}
			else
			{
				$htmlRow[$i] .="
			<td><button type='button' id='cancelBooking' disabled='disabled' class='button orange' style='width:160px;' onclick=\"return false;\">{$lang['TOO_LATE']}</button></td>
		</tr>";
			}
		}
		else
		{
			$htmlRow[$i] .="
			<td><button type='button' id='cancelBooking' class='button orange' style='width:160px;' onclick=\"cancelBooking('{$row['id']}');return false;\">{$lang['CANCEL_BOOKING']}</button></td>
		</tr>";
		}
	}
	else
	{
		$htmlRow[$i] .="
			<td><button type='button' id='cancelBooking' disabled='disabled' class='button orange' style='width:160px;' onclick=\"return false;\">{$lang['PLAYED']}</button></td>
		</tr>";
	}

	$table[$j] .= $htmlRow[$i];
}
$tfoot .= "<input type='hidden' name='finalBookingGroupID' id='finalBookingGroupID' value='{$j}'/></tfoot>
</table>";
foreach ( $table as $bookGroup )
{
	echo $bookGroup;
	echo "
	</tbody>";
	echo $tfoot;
}
?>
</div> <!--bookingGroupArea padding:5px 0px 10px 0px;-->
<div align='center' id='navButtons' style=''>
	<img src='./img/arrow-prev.png' onclick="changeHistoryPage('-1')" />
	<img src='./img/arrow-next.png' onclick="changeHistoryPage('+1')"/>
</div>
</div> <!-- bookHistoryFrame -->

<div class='options_frame' width='100%' style='margin-bottom:10px 30px 0px 30px;' >
	<table width='100%' style='padding:0px 20px 10px 20px;'>

		<tr>
			<th align="center" colspan="2" ><h4><i><?php echo $lang['CUSTOMER_DETAILS']; ?></i></h4></th>
		</tr>
		
		<tr>
			<td width='200px' align="right" style='line-height:2.5'><?php echo $lang['FULL_NAME']; ?>:</td>
			<td style="padding-left:20px;">
				<input type="text" id="newName" name="name" value="<?php echo $_SESSION['name']; ?>" />
				</td>
		</tr>
		
		<tr>
			<td width='200px' align="right" style='line-height:2.5'><?php echo $lang['EMAIL_ADDRESS']; ?>:</td>
			<td style="padding-left:20px;">
				<input type="text" name="newEmail" id="newEmail" readonly="readonly" value="<?php echo $_SESSION['email']; ?>" />
			</td>
		</tr>

		<tr>
			<td  width='200px' align="right" style='line-height:2.5'><?php echo $lang['CONTACT_PHONE']; ?>:</td>
			<td style="padding-left:20px;">
				<input type="text" name="newphone" id="newPhone" readonly="readonly" value="<?php echo $_SESSION['phone']; ?>" />
			</td>
		</tr>

		<tr>
			<td  width='200px' align="right" style='line-height:2.5'><?php echo $lang['NOTIFICATIONS_CHALLENGE']; ?>:</td>
			<td style="padding-left:20px;">
<?php 

if ( $_SESSION['notification_challenges'] == 'TRUE' || $_SESSION['notification_challenges'] == '1')
{
?>
				<form>
					<input type="radio" name="matchNotifications" id="challengeYes" value="1" checked="checked" /><?php echo $lang['YES']; ?>
					<input type="radio" name="matchNotifications" id="challengeNo" value="0"/><?php echo $lang['NO']; ?>
				</form>
<?php
}
else
{
?>
				<form>
					<input type="radio" name="matchNotifications" id="challengeYes" value="1" /><?php echo $lang['YES']; ?>
					<input type="radio" name="matchNotifications" id="challengeNo" value="0" checked="checked" /><?php echo $lang['NO']; ?>
				</form>
<?php
}
?>
			</td>
		</tr>
		<tr>
			<td width='200px'></td>
			<td style="padding-left:20px;"><font color="#FF0000"><?php echo $lang['NOTIFICATIONS_EXPL']; ?></font></td>
		</tr>
		
		<tr>
			<td align="left" colspan="2"><u><?php echo $lang['CHANGE_PSW']; ?>:</u></td>
		</tr>

		<tr>
			<td width='200px' align="right"style='line-height:2.5;'><?php echo $lang['NEW_PSW']; ?>:</td>
			<td style="padding-left:20px;">
				<input type="password" id="newPassword" name="newPassword" />
			</td>
		</tr>
		
		<tr>
			<td width='200px' align="right"style='line-height:2.5;'><?php echo $lang['RE_PSW']; ?>:</td>
			<td style="padding-left:20px;">
				<input type="password" id="newPassword2" name="newPassword2" />
			</td>
		</tr>

		<tr>
			<td colspan="2" style="line-height:2.5; text-align:right;"><?php echo $lang['SAVE_EXPL']; ?>:</td>
		</tr>

		<tr>
			<td colspan="2" style="text-align:right;">
				<input type="password" id="oldPassword" name="oldPassword" />
				<button type="button" id="saveMemberOptions"class="button orange" style="width:120px;"><?php echo $lang['SAVE']; ?></button>
			</td>
		</tr>
	</table>
</div>
</div>

<!-- BOOKING NAVIGATION JAVASCRIPT CODE -->
	<script type="text/javascript" src="./scripts/js/historyNavigation.js"></script>
<!-- CANCELATION JAVASCRIPT CODE -->
	<script type="text/javascript" src="./scripts/js/cancelBooking.js"></script>
<!-- MEMBER OPTIONS/DETAILS JAVASCRIPT CODE -->
    <script type="text/javascript" src="./scripts/js/memberOptionsSave.js"></script>