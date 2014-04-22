<div>
	<input type='hidden' name='direction' id='direction' value='ASC'/>
	<div class='settings_frame' width='100%' style='margin:10px 15px 10px 15px;'>
		<table id='member_list' width='100%' style='padding:0px 10px 10px 10px; table-layout:fixed; word-wrap:break-word;'>
			<thead>
			<tr>
				<th align='left' colspan='3' style='padding-left:10%' ><h3><i>Members Status</i></h3></th>
			</tr>
			
			<tr>
				<td id='clck1' width='' align='center' style='cursor:pointer; border-bottom-style:double; line-height:2.5' onclick="getReputation('name','<?php echo $_SESSION['arenaID']; ?>')"><b>Name</b></td>
				<td width='' align='center' style='border-bottom-style:double; line-height:2.5'><b>Phone</b></td>
				<td width='' align='center' style='border-bottom-style:double; line-height:2.5'><b>E-mail</b></td>
				<td width='' align='center' style='border-bottom-style:double; line-height:2.5'><b>Reg</b></td>
				<td width='' align='center' style='cursor:pointer; border-bottom-style:double; line-height:2.5'onclick="getReputation('reputation','<?php echo $_SESSION['arenaID']; ?>')"><b>Rep</b></td>
				<td width='' align='center' style='border-bottom-style:double; line-height:2.5'><b>Notes</b></td>
				<td width='' align='center' style='border-bottom-style:double; line-height:2.5'><b></b></td>
			</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<script type='text/javascript' src='./scripts/js/reputation.js'></script>		<!-- REPUTATION CODE -->