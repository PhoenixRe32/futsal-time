<script type='text/javascript'>
	$(document).ready(function() {
		$('.fields').css('width',schedule_width-30);
		$('#datepicker').datepicker( {
			showOtherMonths: true,
			selectOtherMonths: true,
			showOn:'focus',
			dateFormat: 'dd-mm-yy DD',
			onSelect: function(dateText, inst) {
				var dateObj = $('#datepicker').datepicker('getDate');
				var dateText = $.datepicker.formatDate('dd-mm-yy DD', dateObj);
				offset = 0;
				getSchedule(dateText);
			}
		});
		
		$("#datepicker").datepicker("setDate", new Date());
		var dateObj = $('#datepicker').datepicker('getDate');
		var dateText = $.datepicker.formatDate('dd-mm-yy DD', dateObj);
		getSchedule(dateText);
	});
</script>
 
<div id='dtTitle' style='display:none;' ></div>
<div style='display: block; margin-left: auto; margin-right: auto; text-align:center;'>
	<input type="text" id="datepicker" readonly='readonly' style='height:32px; text-align:center; font-size: 18px; font-weight:bold; background-color:Gainsboro;'/>
	<input type='button' value='SMS' style='height:32px; text-align:center; font-size: 18px; font-weight:bold; background-color:Gainsboro;' onclick="sendScheduleBySms('<?php echo $_SESSION['nick']; ?>')"/>
</div>

<div class='fields'>
		<input type='hidden' name='rr' id='rr' value='<?php if ( $_SESSION['rr'] != 0 ) echo $_SESSION['rr']*1000; else echo 86400000; ?>'/>
		<input type='hidden' name='sfM' id='sfM' value='<?php echo $_SESSION['sfM']; ?>'/>
		<div style='float:left; padding-top:5px;width:42px'>
			<b><i>TIME</i></b>
		</div>
	<?php
		include_once(dirname(__FILE__)."/scripts/php/databaseClass.php");
		
		$database = new MyDBManager();
		$statement = "SELECT * FROM fields_{$_SESSION['nick']} WHERE type = 'S';";
		$fieldsInfo = $database->fetchSet($statement);
		$numFields = count($fieldsInfo);
		echo "<input type='hidden' name='fnum' id='fnum' value='{$numFields}'/>";
		
		$html = array();
		foreach ( $fieldsInfo as $field )
		{
			$html[$field['fieldId']] = "
		<div style='background-color:#808000; margin:5px; float:left;'>
			<div class='customScr'>
				Field {$field['fieldId']} ({$field['fieldSize']})
			</div>";
		}
		foreach ( $html as $td )

			echo $td."
		</div>";
	?>
</div>
<div id='schedule' style='overflow:auto;'>
</div>

<script type="text/javascript" src="./scripts/js/schedule.js"></script>			<!-- SCHEDULE CODE -->
<script type="text/javascript" src="./scripts/js/livesearch.js"></script>		<!-- LIVE SEARCH CODE -->
<script type="text/javascript" src="./scripts/js/match_details.js"></script>	<!-- GAME DETAILS CODE -->
<script type="text/javascript" src="./scripts/js/reservations.js"></script>		<!-- GAME RESERVE/CANCEL CODE -->
<script type="text/javascript" src="./scripts/js/sendSMS.js"></script>			<!-- SMS SEND CODE -->