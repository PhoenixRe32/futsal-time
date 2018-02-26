<?php 
	if ( !isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn'] )
	{
		?>
		<div class='reservation_box' id='reservation_box'>
			<a class="boxclose" id="reservation_close" tabindex='600'></a>
			
			<div id='reservation_form'>
				<fieldset>
					<legend>Reservation Form for <b><i><span id='dateTime' style='display:none;'>Someday @ Sometime</span></i></b> <b><i><span id='arena'>Somewhere<span/></i></b></legend>
					
					<input type='hidden' name='arenaID' id='arenaID' value=''/>
					<input type='hidden' name='arenaSize' id='arenaSize' value=''/>
					<input type='hidden' name='acceptChal' id='acceptChal' value='0'/>
					<input type='hidden' name='challenges' id='challenges' value='0'/>
					<input type='hidden' name='reservation_form_visible' id='reservation_form_visible' value='0'/>
					
					<div style='margin-left:42px;'>
						<div id='customer_details' style="float:left; margin-right:50px;">
							<div class='short_explanation'><?php echo $lang['GAME_DETAILS']; ?></div>

							<div class='container'>
								<label for='gameDate' ><?php echo $lang['DATE']; ?>: </label><br/>
								<input type='text' name='gameDate' id='gameDate' value='' maxlength="50" readonly='readonly' tabindex='610'/><br/>
								<span id='reserve_date_errorloc' class='error'></span>
							</div>
							
							<div class='container'>
								<label for='gameTime' ><?php echo $lang['TIME']; ?>:</label><br/>
								<input type='text' name='gameTime' id='gameTime' value='' maxlength="50" readonly='readonly' tabindex='620'/><br/>
								<span id='reserve_time_errorloc' class='error'></span>
							</div>
							
							<div class='container' style='display:none'>
								<label for='gameDuration' ><?php echo $lang['DURATION']; ?>:</label><br/>
								<table id='game_duration_options'>
									<tr>
										<td width='50%'>
											<input type="radio" name="gameDuration" value="1" checked="checked"/> One(1) Hour
										</td>
										<td width='50%'>
											<input type="radio" name="gameDuration" value="2" disabled='disabled' style='display:none'/><!--Two(2) Hours-->
										</td>
									</tr>
								</table>
								<span id='reserve_gameDuration_errorloc' class='error'></span>
							</div>
							
							<div class='container'>
								<label for='gameField' ><?php echo $lang['GAME_TYPE']; ?>:</label><br/>
								<table id='game_type_options'>
									<tr>
										<td id='game_type1_cont' width='50%'>
											<input type="radio" id="game_type1" name="gameType" value="MATCH" checked="checked" tabindex='630'/><?php echo $lang['MATCH']; ?>
										</td>
										<td id='game_type2_cont' width='50%'>
											<input type="radio" id="game_type2" name="gameType" value="CHALLENGE" tabindex='630'/><?php echo $lang['CHALLENGE']; ?>
										</td>
									</tr>
								</table>
								<span id='reserve_gametype_errorloc' class='error'></span>
							</div>
							
							<div class='container' style='display:none'>
								<label for='gameField' ><?php echo $lang['FIELD']; ?>:</label><br/>
								<input type='text' name='gameField' id='gameField' value='' maxlength="50" readonly='readonly' tabindex='640'/><br/>
								<span id='reserve_field_errorloc' class='error'></span>
							</div>
							
							<div class='container'>
								<label for='arenaPhone' ><?php echo $lang['ARENA_PHONE']; ?>:</label><br/>
								<input type='text' name='arenaPhone' id='arenaPhone' value='' maxlength="50" readonly='readonly' tabindex='650'/><br/>
								<span id='reserve_phone_errorloc' class='error'></span>
							</div>
						</div>
						
						<div id='game_details' style="float:left">
							<div class='short_explanation'><?php echo $lang['CUSTOMER_DETAILS']; ?></div>

							<div class='container'>
								<label for='name' ><?php echo $lang['FULL_NAME']; ?>*: </label><br/>
								<input type='text' name='customerName' id='customerName' value='' maxlength="50"  tabindex='670'/><br/>
								<span id='reserve_name_errorloc' class='error'></span>
							</div>
							
							<div class='container'>
								<label for='customerEmail' ><?php echo $lang['EMAIL_ADDRESS']; ?>*:</label><br/>
								<input type='text' name='customerEmail' id='customerEmail' value='' maxlength="50"  tabindex='680'/><br/>
								<span id='reserve_email_errorloc' class='error'></span>
							</div>
							
							<div class='container'>
								<label for='customerPhone' ><?php echo $lang['CONTACT_PHONE']; ?>*:</label><br/>
								<input type='text' name='customerPhone' id='customerPhone' value='' maxlength="50"  tabindex='690'/><br/>
								<span id='reserve_phone_errorloc' class='error'></span>
							</div>

							<div class='container'>
								<input type='button' id='reservationSubmit' name='submit' value='<?php echo $lang['RESERVE']; ?>' style='float:right' tabindex='700'/>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
		<?php
	}
	else
	{
		?>
		<div class='reservation_box' id='reservation_box' >
			<a class="boxclose" id="reservation_close" tabindex='600'></a>
			
			<div id='reservation_form'>
				<fieldset>
					<legend>Reservation Form for <b><i><span id='dateTime' style='display:none;'>Someday @ Sometime</span></i></b> <b><i><span id='arena'>Somewhere</span></i></b></legend>
					
					<input type='hidden' name='arenaID' id='arenaID' value=''/>
					<input type='hidden' name='arenaSize' id='arenaSize' value=''/>
					<input type='hidden' name='acceptChal' id='acceptChal' value='0'/>
					<input type='hidden' name='challenges' id='challenges' value='0'/>
					<input type='hidden' name='reservation_form_visible' id='reservation_form_visible' value='0'/>
					
					<div style='margin-left:42px;'>
						<div id='customer_details' style="float:left; margin-right:50px;">
							<div class='short_explanation'><?php echo $lang['GAME_DETAILS']; ?></div>

							<div class='container'>
								<label for='gameDate' ><?php echo $lang['DATE']; ?>: </label><br/>
								<input type='text' name='gameDate' id='gameDate' value='' maxlength="50" readonly='readonly' tabindex='610'/><br/>
								<span id='reserve_date_errorloc' class='error'></span>
							</div>
							
							<div class='container'>
								<label for='gameTime' ><?php echo $lang['TIME']; ?>:</label><br/>
								<input type='text' name='gameTime' id='gameTime' value='' maxlength="50" readonly='readonly' tabindex='620'/><br/>
								<span id='reserve_time_errorloc' class='error'></span>
							</div>
							
							<div class='container' style='display:none'>
								<label for='gameDuration' ><?php echo $lang['DURATION']; ?>:</label><br/>
								<table id='game_duration_options'>
									<tr>
										<td width='50%'>
											<input type="radio" name="gameDuration" value="1" checked="checked"/> One(1) Hour
										</td>
										<td width='50%'>
											<input type="radio" name="gameDuration" value="2" disabled='disabled' style='display:none'/><!--Two(2) Hours-->
										</td>
									</tr>
								</table>
								<span id='reserve_gameDuration_errorloc' class='error'></span>
							</div>
							
							<div class='container'>
								<label for='gameType' ><?php echo $lang['GAME_TYPE']; ?>:</label><br/>
								<table id='game_type_options'>
									<tr>
										<td id='game_type1_cont' width='50%'>
											<input type="radio" id="game_type1" name="gameType" value="MATCH" checked="checked" tabindex='630'/><?php echo $lang['MATCH']; ?>
										</td>
										<td id='game_type2_cont' width='50%'>
											<input type="radio" id="game_type2" name="gameType" value="CHALLENGE" tabindex='630'/><?php echo $lang['CHALLENGE']; ?>
										</td>
									</tr>
								</table>
								<span id='reserve_gametype_errorloc' class='error'></span>
							</div>
							
							<div class='container' style='display:none'>
								<label for='gameField' ><?php echo $lang['FIELD']; ?>:</label><br/>
								<input type='text' name='gameField' id='gameField' value='' maxlength="50" readonly='readonly' tabindex='640'/><br/>
								<span id='reserve_field_errorloc' class='error'></span>
							</div>
							
							<div class='container'>
								<label for='arenaPhone' ><?php echo $lang['ARENA_PHONE']; ?>:</label><br/>
								<input type='text' name='arenaPhone' id='arenaPhone' value='' maxlength="50" readonly='readonly' tabindex='650'/><br/>
								<span id='reserve_phone_errorloc' class='error'></span>
							</div>
						</div>
						
						<div id='game_details' style="float:left">
							<div class='short_explanation'><?php echo $lang['CUSTOMER_DETAILS']; ?></div>

							<div class='container'>
								<label for='customerName' ><?php echo $lang['FULL_NAME']; ?>*: </label><br/>
								<input type='text' name='customerName' id='customerName' value='<?php echo $_SESSION['name']; ?>' maxlength="50" readonly='readonly' tabindex='670'/><br/>
								<span id='reserve_name_errorloc' class='error'></span>
							</div>
							
							<div class='container'>
								<label for='customerEmail' ><?php echo $lang['EMAIL_ADDRESS']; ?>*:</label><br/>
								<input type='text' name='customerEmail' id='customerEmail' value='<?php echo $_SESSION['email']; ?>' maxlength="50" readonly='readonly' tabindex='680'/><br/>
								<span id='reserve_email_errorloc' class='error'></span>
							</div>
							
							<div class='container'>
								<label for='customerPhone' ><?php echo $lang['CONTACT_PHONE']; ?>*:</label><br/>
								<input type='text' name='customerPhone' id='customerPhone' value='<?php echo $_SESSION['phone']; ?>' maxlength="50" readonly='readonly' tabindex='690'/><br/>
								<span id='reserve_phone_errorloc' class='error'></span>
							</div>

							<div class='container'>
								<input type='button' id='reservationSubmit' name='submit' value='<?php echo $lang['RESERVE']; ?>' style='float:right' tabindex='700'/>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
		<?php
	}
?>