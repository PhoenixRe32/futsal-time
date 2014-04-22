<?php
	include_once(dirname(__FILE__)."/scripts/php/databaseClass.php");
	$database = new MyDBManager();
	
	$statement = "SELECT id, name FROM arenas;";	
	$arenas = $database->fetchSet($statement);
	if ( $database->getErrorStatus() )
	{
		$database->__destruct(); unset($database);
		$arenas = array();
		$arenas[0] = "Couldn't get arenas...";
	}
	
?>
<table style='width:100%; text-align:center;'>
	<tr><td><input type='text' id="dtTitle" disabled='disabled' style="text-align:center; width:350px;" /></td></tr>
</table>

<div id='SearchArea' style='padding-top:10px;'>
	<table style='margin-left:10px;' border='0' >
		<tr>
			<td>
				<div id="daySelectorCell" class="styled-select" style="overflow:auto;">
					<select id="daySelector"  >
						<option value="-1"><?php echo $lang['DATE']; ?></option>
					</select>
				</div>
			</td>
			
			<td>
				<div id="hourSelectorCell" class="styled-select" style="overflow:auto;">
					<select id="hourSelector" >
					  <option value="16:00:00">16:00</option> <!--0-->
					  <option value="17:00:00">17:00</option> <!--1-->
					  <option value="18:00:00">18:00</option> <!--2-->
					  <option value="19:00:00">19:00</option> <!--3-->
					  <option value="20:00:00">20:00</option> <!--4-->
					  <option value="21:00:00">21:00</option> <!--5-->
					  <option value="22:00:00">22:00</option> <!--6-->
					  <option value="23:00:00">23:00</option> <!--7-->
					</select>
				</div>
			</td>
			
			<td>
				<div id="arenaSelectorCell" class="styled-select" style="overflow:auto;">
					<select id="arenaSelector" >
						<option value="-1">Arena</option>
						<!--<optgroup label="Lefkosia">
							<!--<option value="-22">Lefkosia</option>-->
							<?php
								foreach ( $arenas as $arena )
								{
									echo "
							<option value='{$arena['id']}'>{$arena['name']}</option>
									";
								}
							?>
						<!--</optgroup>-->
						<!--<optgroup label="Lemesos">-->
							<!--<option value="-25">Lemesos</option>-->
						<!--</optgroup>-->
						<!--<optgroup label="Larnaka">-->
							<!--<option value="-24">Larnaka</option>-->
						<!--</optgroup>-->
						<!--<optgroup label="Paphos">-->
							<!--<option value="-26">Paphos</option>-->
						<!--</optgroup>-->
					</select>
				</div>
			</td>
		
			<td>
				<div id="searchButtonCell" class="styled-select" style="">
					<button type="button" class="button orange" style="width:100%;" id="searchButton"><?php echo $lang['SEARCH']; ?></button>
					<input type='hidden' name='search_form_focused' id='search_form_focused' value='0'/>
				</div>
			</td>
		</tr>
	</table>

	<div id='searchResults' style='margin:10px 0px 0px 22px;'>
	</div> <!-- searchResults -->

	<div id='book_legend' style='margin-left:50px;'>
		<table width='715' class='info_frame'>
			<tr>
				<td>
				   <ul>
					  <li><b>5 x 5</b> 	 - <?php echo $lang['5X5']; ?></li><br />
					  <li><b>10 x 10</b> - <?php echo $lang['9X9']; ?></li><br />
					  <li><b>vs ?</b>    - <?php echo $lang['VS']; ?></li>   
					</ul>
										  
				</td>
			</tr>
		</table>
	</div> <!-- book_legend -->
</div> <!-- SearchArea -->


<div id='include_reservation_part' style='display:none'>
	<?php include_once(dirname(__FILE__)."/part_support_reservation.php"); ?>
</div>

<script type="text/javascript" src="./scripts/js/search.js"></script>						<!-- SEARCH JAVASCRIPT CODE -->
<script type="text/javascript" src="./scripts/js/reservationRelatedFunctions.js"></script>	<!-- SEARCH RESULTS TABLES AND RESERVE JAVASCRIPT CODE -->
<script type="text/javascript" src="./scripts/js/sendSMS.js"></script>	
<script type="text/javascript">
	$(document).ready(function() {
		var htmlCode = buildDayList();
		$("#daySelector").html(htmlCode);
		$("#daySelector").focus();
		
		var date = new Date();
		var diff = (date.getHours()) - 16;
		
		if ( diff < 0 )                  $('#hourSelector').prop('selectedIndex',2);
		else if ( diff > 5 && diff < 9 ) $('#hourSelector').prop('selectedIndex',7);
		else                             $('#hourSelector').prop('selectedIndex', diff+2);
		
		var dbDate = $('#daySelector').val();
		var day = $('#daySelector option:selected').text();
	});
</script>