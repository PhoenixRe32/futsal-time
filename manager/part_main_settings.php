<?php 
	include_once(dirname(__FILE__)."/scripts/php/databaseClass.php");
	include_once(dirname(__FILE__)."/scripts/php/arenaRelatedFunctions.php");
	
	// foreach ($_SESSION as $k=>$v) echo $k.' => '.$v.'<BR>';
?>
<script type='text/javascript'>if ( typeof(reccuring)==='undefined' ) ; else {clearInterval(recurring);alert('clear');}</script>
<div>
	<div class='settings_frame' width='100%' style='margin:10px 15px 10px 15px;'>
		<table id='manager_settings' width='100%' style='padding:0px 20px 10px 20px;'>
			<tr>
				<th align='left' colspan='3' style='padding-left:10%;' ><h3><i>ARENA SETTINGS</i></h3></th>
			</tr>
			
			<tr>
				<td width='200px' align='right' style='line-height:2.5'>Booking Limit per customer:</td>
				<td width='275px' style='padding-left:20px;'>
					<select <?php if ( $_SESSION['bl_P'] == '0' ) echo "disabled='disabled'" ?> class='settings' id='bl' style='width:100%;'>
					  <option <?php if ( $_SESSION['bl'] == '100' ) echo "selected='selected'"; ?>  value='100'>No limits</option> <!--0-->
					  <option <?php if ( $_SESSION['bl'] == '1' ) echo "selected='selected'"; ?>  value='1'>1 pending game</option> <!--1-->
					  <option <?php if ( $_SESSION['bl'] == '2' ) echo "selected='selected'"; ?>  value='2'>2 pending games</option> <!--2-->
					  <option <?php if ( $_SESSION['bl'] == '3' ) echo "selected='selected'"; ?> value='3'>3 pending games</option> <!--3-->
					  <option <?php if ( $_SESSION['bl'] == '4' ) echo "selected='selected'"; ?> value='3'>4 pending games</option> <!--4-->
					  <option <?php if ( $_SESSION['bl'] == '5' ) echo "selected='selected'"; ?> value='3'>5 pending games</option> <!--5-->
					</select>
				</td>
				<td style='text-align:left;'>
				</td>
			</tr>
			
			<tr>
				<td width='200px' align='right' style='line-height:2.5'>Time Division for Customer:</td>
				<td width='275px' style='padding-left:20px;'>
					<select <?php if ( $_SESSION['sfC_P'] == '0' ) echo "disabled='disabled'" ?> class='settings' id='divC' style='width:100%;'>
					  <option <?php if ( $_SESSION['sfC'] == '15' ) echo "selected='selected'"; ?>  value='15'>15 minutes</option> <!--0-->
					  <option <?php if ( $_SESSION['sfC'] == '30' ) echo "selected='selected'"; ?>  value='30'>30 minutes</option> <!--1-->
					  <option <?php if ( $_SESSION['sfC'] == '60' ) echo "selected='selected'"; ?> value='60'>60 minutes</option> <!--2-->
					</select>
				</td>
				<td style='text-align:left;'>
				</td>
			</tr>
			
			<tr>
				<td width='200px' align='right' style='line-height:2.5'>Time Division for Manager:</td>
				<td width='275px' style='padding-left:20px;'>
					<select <?php if ( $_SESSION['sfM_P'] == '0' ) echo "disabled='disabled'" ?> class='settings' id='divM' style='width:100%;'>
					  <option <?php if ( $_SESSION['sfM'] == '15' ) echo "selected='selected'"; ?> value='15'>15 minutes</option> <!--0-->
					  <option <?php if ( $_SESSION['sfM'] == '30' ) echo "selected='selected'"; ?> value='30'>30 minutes</option> <!--1-->
					  <option <?php if ( $_SESSION['sfM'] == '60' ) echo "selected='selected'"; ?> value='60'>60 minutes</option> <!--2-->
					</select>
				</td>
				<td style='text-align:left;'>
				</td>
			</tr>
			
			<tr>
				<td width='200px' align='right' style='line-height:2.5'>Schedule Refresh Rate:</td>
				<td width='275px' style='padding-left:20px;'>
					<select <?php if ( $_SESSION['rr_P'] == '0' ) echo "disabled='disabled'" ?> class='settings' id='rf'  style='width:100%;'>
					  <!-- <option <?php if ( $_SESSION['rr'] == '10' ) echo "selected='selected'"; ?> value='10'>10 seconds</option> <!--1-->
					  <option <?php if ( $_SESSION['rr'] == '30' ) echo "selected='selected'"; ?> value='30'>30 seconds</option> <!--1-->
					  <option <?php if ( $_SESSION['rr'] == '60' ) echo "selected='selected'"; ?> value='60'>1 minute</option> <!--3-->
					  <!-- <option <?php if ( $_SESSION['rr'] == '90' ) echo "selected='selected'"; ?> value='90'>1.5 minutes</option> <!--4-->
					  <option <?php if ( $_SESSION['rr'] == '120' ) echo "selected='selected'"; ?> value='120'>2 minutes</option> <!--5-->
					  <option <?php if ( $_SESSION['rr'] == '300' ) echo "selected='selected'"; ?> value='300'>5 minutes</option> <!--6-->
					  <option <?php if ( $_SESSION['rr'] == '600' ) echo "selected='selected'"; ?> value='600'>10 minutes</option> <!--7-->
					  <option <?php if ( $_SESSION['rr'] == '0' ) echo "selected='selected'"; ?> value='0'>Never</option> <!---->
					</select>
				</td>
				<td style='text-align:left;'>
				</td>
			</tr>
		
			<tr>
				<td width='200px' align='right' style='line-height:2.5'>Notify customers with SMS:</td>
				<td width='275px' style='padding-left:20px;'>
					<select disabled='disabled' <?php if ( $_SESSION['smsC_P'] == '0' ) echo "disabled='disabled'" ?> class='settings' id='smsC' style='width:100%;'>
					  <option <?php if ( $_SESSION['smsC'] == '0' ) echo "selected='selected'"; ?>  value='0'>No</option> <!--0-->
					  <option <?php if ( $_SESSION['smsC'] == '1' ) echo "selected='selected'"; ?>  value='1'>Yes</option> <!--1-->
					</select>
				</td>
				<td style='text-align:left;'>
				</td>
			</tr>
			
			<tr>
				<td width='200px' align='right' style='line-height:2.5'>Notify manager with SMS:</td>
				<td width='275px' style='padding-left:20px;'>
					<select disabled='disabled' <?php if ( $_SESSION['smsM_P'] == '0' ) echo "disabled='disabled'" ?> class='settings' id='smsM'  style='width:100%;'>
					  <option <?php if ( $_SESSION['smsM'] == '0' ) echo "selected='selected'"; ?>  value='0'>No</option> <!--0-->
					  <option <?php if ( $_SESSION['smsM'] == '1' ) echo "selected='selected'"; ?>  value='1'>Yes</option> <!--1-->
					</select>
				</td>
				<td style='text-align:left;'>
				</td>
			</tr>
			
			<tr>
				<td width='200px' align='right' style='line-height:2.5'>Contact Phones:</td>
				<td width='275px' style='padding-left:20px;' style='width:100%;'>
					<input <?php if ( $_SESSION['p_P'] == '0' ) echo "disabled='disabled'" ?> type='text' class='settings' name='contact' id='contact' style='width:100%;' value='<?php echo $_SESSION['p']; ?>' />
				</td>
				<td style='text-align:left;'>
				</td>
			</tr>
			
			<tr <?php if ( $_SESSION['smsM'] == '0' ) echo "style='display:none'"; ?> class='smsOptions'>
				<td width='200px' align='right' style='line-height:2.5'>SMS Contact Phone:</td>
				<td width='275px' style='padding-left:20px;'>
					<input <?php if ( $_SESSION['smsP_P'] == '0' ) echo "disabled='disabled'" ?> type='text' class='settings' name='smsContact' id='smsContact' style='width:100%;' value='<?php echo $_SESSION['smsP']; ?>' />
				</td>
				<td style='text-align:left;'>
				</td>
			</tr>
			
		</table>
	</div>
</div>

<script type='text/javascript' src='./scripts/js/settings.js'></script>		<!-- SETTINGS CODE -->