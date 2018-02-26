
			<tr <?php if ( $_SESSION['smsM'] == '0' ) echo "style='display:none'"; ?> class='smsOptions'>
				<td width='200px' align='right' style='line-height:2.5'>SMS Contact Times:</td>
				<td width='275px' style='padding-left:20px;'>
					<select <?php if ( $_SESSION['smsP_P'] == '0' ) echo "disabled='disabled'" ?> id='smsTimesF' style='width:80px;'>
					<?php 
						for ( $i = 0; $i < 24; $i++ )
						{
							$h = $i;
							if ( $h < 10 ) $h = '0'.$h;
							for ( $j = 0; $j < 60; $j+=30 )
							{
								$m = $j;
								if ( $m < 10 ) $m = '0'.$m;
								echo "
						<option value='$h:$m:00'>$h:$m</option> <!--".($i*12+$j/5)."-->";
							}
						}
					?>
					</select>
					
					<i>to</i>
					<select <?php if ( $_SESSION['smsP_P'] == '0' ) echo "disabled='disabled'" ?> id='smsTimesT' style='width:90px;'>
					<?php 
						for ( $i = 0; $i < 24; $i++ )
						{
							$h = $i;
							if ( $h < 10 ) $h = '0'.$h;
							for ( $j = 0; $j < 60; $j+=30 )
							{
								$m = $j;
								if ( $m < 10 ) $m = '0'.$m;
								echo "
						<option value='$h:$m:00'>$h:$m</option> <!--".($i*12+$j/5)."-->";
							}
						}
					?>
				</td>
				<td style='text-align:left;'>
					<input <?php if ( $_SESSION['smsP_P'] == '0' ) echo "disabled='disabled'" ?> type='button' style='width:100px;' value='Add' onclick='addSMSPeriod()'/>
				</td>
			</tr>
			
			<?php
				$database = new MyDBManager();
				$setTimes = getSMSTimes($database, $_SESSION['arenaID']);
				foreach ( $setTimes as $per )
				{
					$s = substr($per['startAt'], 0, 5);
					$e = substr($per['endAt'], 0, 5);
					echo "
			<tr "; 
					if ( $_SESSION['smsM'] == '0' ) echo "style='display:none'";
					echo" id='smsPer{$per['id']}' class='smsOptions'>
				<td width='200px' align='right' style='line-height:2.5'></td>
				<td width='275px' style='padding-left:20px;'>
					<input type='text' ";
					if ( $_SESSION['smsP_P'] == '0' ) echo "disabled='disabled'";
					else echo "readonly='readonly'";
					echo " style='width:100%; text-align:center;' value='{$s} to {$e}'/>
				</td>
				<td>
					<input "; 
					if ( $_SESSION['smsP_P'] == '0' ) echo "disabled='disabled'";
					echo " type='button' value='Remove' style='width:100px;' onclick='removeSMSPeriod({$per['id']})' />
				</td>	
			</tr>";
						}
			?>
