<div>
	<div class='settings_frame' width='100%' style='margin:10px 15px 10px 15px;'>
		<table id='schedule_funtions' width='100%' style='padding:0px 20px 10px 20px;'>
			<tr>
				<th align='left' colspan='3' style='padding-left:10%;'><h3><i>SCHEDULE FUNCTIONS</i></h3></th>
			</tr>
			
			<!--<tr>
				<td width='150px' align='right' style='line-height:2.5'><i>Reccuring match</i>:</td>
				<td width='275px' style='padding-left:20px;'>
					<select class='settings' id='schedFr' style='width:100%;'>
					  <option value='-1'>Frequency</option>
					  <option value='1'>Every Day</option>
					  <option value='2'>Every Other Day</option>
					  <option value='7'>Every Week</option>
					</select>
				</td>
				<td style='text-align:left;'>
					From To
				</td>
			</tr>-->
		</table>
	</div>
	
	<div class='settings_frame' width='100%' style='margin:10px 15px 10px 15px;'>
		<table id='schedule_funtions' width='100%' style='padding:0px 20px 10px 20px;'>
			<tr>
				<th align='left' colspan='3' style='padding-left:10%;'><h3><i>MEMBERS FUNCTIONS</i></h3></th>
			</tr>
			
			<tr>
				<td width='150px' align='right' style='line-height:2.5'><i><u>Add Members</u></i>:</td>
				
				<td width='275px' style='padding-left:20px;'>
					<label for='newName'>Name: </label> <input type="text" id="newName" name="newName" />
				</td>
				
				<td style=''>
					<label for='newPhone'>Phone: </label> <input type="text" id="newPhone" name="newPhone" />
				</td>
				
				<td style='text-align:left;'>
					<label for='currentPassword'>Current Password: </label> <input type="password" id="currentPassword" name="currentPassword" />
					<button disabled onclick="addMember();"> Add </button>
				</td>
			</tr>
		</table>
	</div>
	
	<div class='settings_frame' width='100%' style='margin:10px 15px 10px 15px;'>
		<table id='manager_funtions' width='100%' style='padding:0px 20px 10px 20px;'>
			<tr>
				<th align='left' colspan='3' style='padding-left:10%;' ><h3><i>MANAGER FUNCTIONS</i></h3></th>
			</tr>
			
			<tr>
				<td width='150px' align='right' style='line-height:2.5'><i><u>Change password</u></i>:</td>
				<td width='275px' style='padding-left:20px;'>
					<label for='newPassword'>New Password: </label> <input type="password" id="newPassword" name="newPassword" />
				</td>
				<td style='text-align:left;'>
					<label for='currentPassword'>Current Password: </label> <input type="password" id="currentPassword" name="currentPassword" />
					<button onclick="updatePassword();"> Update </button>
				</td>
			</tr>
		</table>
	</div>
</div>

<script type='text/javascript' src='./scripts/js/tools.js'></script>		<!-- Funtions CODE -->