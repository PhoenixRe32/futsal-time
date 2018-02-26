<script type="text/javascript" src="./scripts/js/jquery-ui-1.10.2.custom.min.js"></script>	<!-- JQUERY UI -->
<link rel="stylesheet" type="text/css" href="./css/jquery-ui-1.10.2.custom.min.f.css"/>		<!-- JQUERY UI CSS -->

<table style='margin-left:58px; padding-right:20px; width:100%; text-align:center;'>
	<tr><td><input type='text' id="datepicker" readonly='readonly' style="text-align:center; width:300px; cursor:pointer;" /></td></tr>
</table>

<div id='SearchArea' style='padding-top:10px;'>
	<div id='searchResults' style='margin:10px 0px 0px 20px;'>
	</div> <!-- searchResults -->

	<div id='book_legend' style='margin:0px 88px 0px 88px;'>
		<table class='info_frame' style='width:100%'>
			<tr>
				<td>
				   <ul>
					  <li><b>5 x 5</b> 	 - <?php echo $lang['5X5']; ?></li><br />
					  <li><b>9 x 9</b> - <?php echo $lang['9X9']; ?></li><br />
					  <li><b>vs ?  </b>    - <?php echo $lang['VS']; ?></li>   
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