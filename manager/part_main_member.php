<div>
	<input type='hidden' name='direction' id='direction' value='ASC'/>
	<div class='settings_frame' width='100%' style='margin:10px 15px 10px 15px;'>
		<table id='member_list' width='100%' style='padding:0px 10px 10px 10px;  word-wrap:break-word; -ms-word-break: break-all; word-break: break-all; word-break: break-word; -webkit-hyphens: auto; -moz-hyphens: auto; -ms-hyphens: auto;'>
			<thead>
			<tr>
				<th align='left' colspan='3' style='padding-left:10%' ><h3><i>Members Status</i></h3></th>
			</tr>
			
			<tr>
				<td align='center' style='width:5%; border-bottom-style:double; line-height:2.5'><b>#</b></td>
				<td id='clck1' align='center' style='width:25%; cursor:pointer; border-bottom-style:double; line-height:2.5' onclick="getReputation('name','<?php echo $_SESSION['arenaID']; ?>')"><b>Name</b></td>
				<td align='center' style='width:10%; border-bottom-style:double; line-height:2.5'><b>Phone</b></td>
				<td align='center' style='width:8%; border-bottom-style:double; line-height:2.5'><b>E-mail</b></td>
				<td align='center' style='width:7%; border-bottom-style:double; line-height:2.5'><b>Reg</b></td>
				<td align='center' style='width:10%; cursor:pointer; border-bottom-style:double; line-height:2.5'onclick="getReputation('reputation','<?php echo $_SESSION['arenaID']; ?>')"><b>Rep</b></td>
				<td align='center' style='width:30%; border-bottom-style:double; line-height:2.5'><b>Notes</b></td>
				<td align='center' style='width:5%; border-bottom-style:double; line-height:2.5'><b></b></td>
			</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<script type='text/javascript' src='./scripts/js/reputation.js'></script>		<!-- REPUTATION CODE -->