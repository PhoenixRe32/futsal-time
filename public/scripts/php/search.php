<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/languages/lang.{$_POST['lang']}.php");
	include_once (dirname(__FILE__)."/searchRelatedFunctions.php");

	$date = $_POST['date'];
	$hour = $_POST['hour'];
	$arena = $_POST['arena'];
	
	$html = ''; 
	$ajaxResponse = '';
	$database = new MyDBManager();

	$statement = "
		SELECT	name, phones, nick, slotFormatClient, smsManager, smsCustomer
		FROM	arenas 
		INNER JOIN arenaSettings ON arenas.id = arenaSettings.id 
		WHERE	arenas.id={$arena};";	
	$arenaInfo = $database->fetchRow($statement);
	if ( $database->getErrorStatus() )
	{
		$database->__destruct(); unset($database);
		echo $html;	
		exit();
	}
	$name = $arenaInfo['name'];
	$contact = $arenaInfo['phones'];
	$nick = $arenaInfo['nick'];
	$slotFormat = $arenaInfo['slotFormatClient'];
	$smsC = $arenaInfo['smsCustomer'];
	$smsM = $arenaInfo['smsManager'];
	unset($arenaInfo);
	
	$statement = "SELECT * FROM fields_{$nick} WHERE type='D' ORDER BY fieldSize ASC;;";
	$fieldInfo = $database->fetchSet($statement);
	if ( $database->getErrorStatus() )
	{
		$database->__destruct(); unset($database);
		echo $html;	
		exit();
	}
	$doubles = array();
	$sizes = array();
	foreach ( $fieldInfo as $doubleField )
	{
		$doubles[$doubleField['fieldId']] = explode(',', $doubleField['containing']);
		$sizes[$doubleField['fieldId']] = $doubleField['fieldSize'];
	}
	unset($fieldInfo);
	
	$ajaxResponse .= "
<response>";
	if ( $arena > 0 )
	{
		$ajaxResponse .= arenaFreeSlotSearch($database, $date, $hour, $nick, $slotFormat, $doubles, $sizes);
	}
	else
	{
		$arena *= -1; // no support yet
	}
	$ajaxResponse .= "
</response>";
	
	$fp = fopen("../../search_results/results.xml", 'w'); fwrite($fp, $ajaxResponse); fclose($fp);
	
	$html = "
	<section class='containerSection'>";
	$xmlIter = new SimpleXMLIterator($ajaxResponse);
	for( $xmlIter->rewind(); $xmlIter->valid(); $xmlIter->next() ) 
	{
		$node = $xmlIter->current(); // arena node
		
		// top table
		$html .= "
		<div class='search_table'>";
		$html .= "
			<ul style='width:44px;'>
				<li>
				&nbsp;
				</li>";
		foreach ( $node->slot[0] as $k => $v ) 
		{
			if ( trim($k) == 'challenges' ) continue;
			if ( substr($k,0,1) != 'c' )
				$html .=  "
				<li>
					<div class='labelTag'>
						 <b>".substr($k,2)."</b>
					</div>
				</li>";
			else
				$html .=  "
				<li>
					<div class='labelTag'>
						 <b>VS ".substr($k,2,1)."</b>
					</div>
				</li>";
		}
		$html .= "
			</ul>";
			
		for ( $r = 0; $r < 6; $r+=1 )	//regurlar is 10 +=2
		{
			$numChall = $node->slot[$r]->challenges;

			$html .= "
			<ul class='Hover'>";
			//time
			$html .= "
				<li>".substr($node->slot[$r]['date_time'], 11, 5)."</li>";
			
			foreach ( $node->slot[$r] as $k => $v ) 
			{
				if ( trim($k) == 'challenges' ) continue;
				if ( substr($k,0,1) == 'f')
				{
					if ( $v == 0 )
					{
						$html .= "
						<li><button class='bookButton disabled' onclick=\"addAlert('".substr($k,2)."', {$arena}, '{$node->slot[$r]['date_time']}', 'PENDING')\">
							<div class='title' disabled='disabled'>{$lang['AVAILABLE']}</div>
							<div class='available' disabled='disabled'>0</div>
						</button></li>";
					}
					else
					{
						$html .= "
						<li><button class='bookButton yellow' onclick=\"reservation('".substr($k,2)."', '{$name}', '{$arena}', '{$node->slot[$r]['date_time']}', 'PENDING', '{$numChall}', '{$contact}', this, {$smsM}, {$smsC});return false;\"> <div class='title'>{$lang['AVAILABLE']}</div> <div class='available'>{$v}</div></button></li>";
					}
				}
				else if ( substr($k,0,1) == 'd')
				{
					if ( $v == 0 )
					{
						$html .= "
						<li><button class='bookButton disabled'>
							<div class='title' disabled='disabled'>{$lang['AVAILABLE']}</div>
							<div class='available' disabled='disabled'>0</div>
						</button></li>";
					}
					else
					{
						$html .= "
						<li><button class='bookButton green' onclick=\"reservation('".substr($k,2)."', '{$name}', '{$arena}', '{$node->slot[$r]['date_time']}', 'PENDING', '{$numChall}', '{$contact}', this, {$smsM}, {$smsC});return false;\"> <div class='title'>{$lang['AVAILABLE']}</div> <div class='available'>{$v}</div></button></li>";
					}
				}
				else if ( substr($k,0,1) == 'c')
				{
					if ( $v == 0 )
					{
						$html .= "
						<li><button class='bookButton disabled'>
							<div class='title' disabled='disabled'>{$lang['AVAILABLE']}</div>
							<div class='available' disabled='disabled'>0</div>
						</button></li>";
					}
					else
					{
						$html .= "
						<li><button class='bookButton orange' onclick=\"reservation('".substr($k,2)."', '{$name}', '{$arena}', '{$node->slot[$r]['date_time']}', 'ACCEPT', '{$numChall}', '{$contact}', this, {$smsM}, {$smsC});return false;\"> <div class='title'>{$lang['AVAILABLE']}</div> <div class='available'>{$v}</div></button></li>";
					}
				}
			}
			
			$html .= "
			</ul>";
		}
		//end of top table
		$html .= "
		</div>
		<br style='clear:both;'/>
		<hr width='' style='margin:15px 66px 15px 44px;'/>";
/* 	
		//bottom table
		$html .= "
		<div class='search_table'>";
		
		$html .= "
			<ul style='width:44px;'>
				<li>
				&nbsp;
				</li>";
		foreach ( $node->slot[0] as $k => $v ) 
		{
			if ( trim($k) == 'challenges' ) continue;
			if ( substr($k,0,1) != 'c' )
				$html .=  "
				<li>
					<div class='labelTag'>
						 <b>".substr($k,2)."</b>
					</div>
				</li>";
			else
				$html .=  "
				<li>
					<div class='labelTag'>
						 <b>VS ".substr($k,2,1)."</b>
					</div>
				</li>";
		}
		$html .= "
			</ul>";
			
		for ( $r = 1; $r < 10; $r+=2 )
		{
			$numChall = $node->slot[$r]->challenges;

			$html .= "
			<ul class='Hover'>";
			//time
			$html .= "
				<li>".substr($node->slot[$r]['date_time'], 11, 5)."</li>";
			
			foreach ( $node->slot[$r] as $k => $v ) 
			{
				if ( trim($k) == 'challenges' ) continue;
				if ( substr($k,0,1) == 'f')
				{
					if ( $v == 0 )
					{
						$html .= "
						<li><button class='bookButton disabled' onclick=\"addAlert('".substr($k,2)."', {$arena}, '{$node->slot[$r]['date_time']}', 'PENDING')\">
							<div class='title' disabled='disabled'>{$lang['AVAILABLE']}</div>
							<div class='available' disabled='disabled'>0</div>
						</button></li>";
					}
					else
					{
						$html .= "
						<li><button class='bookButton yellow' onclick=\"reservation('".substr($k,2)."', '{$name}', '{$arena}', '{$node->slot[$r]['date_time']}', 'PENDING', '{$numChall}', '{$contact}', this, {$smsM}, {$smsC});return false;\"> <div class='title'>{$lang['AVAILABLE']}</div> <div class='available'>{$v}</div></button></li>";
					}
				}
				else if ( substr($k,0,1) == 'd')
				{
					if ( $v == 0 )
					{
						$html .= "
						<li><button class='bookButton disabled'>
							<div class='title' disabled='disabled'>{$lang['AVAILABLE']}</div>
							<div class='available' disabled='disabled'>0</div>
						</button></li>";
					}
					else
					{
						$html .= "
						<li><button class='bookButton green' onclick=\"reservation('".substr($k,2)."', '{$name}', '{$arena}', '{$node->slot[$r]['date_time']}', 'PENDING', '{$numChall}', '{$contact}', this, {$smsM}, {$smsC});return false;\"> <div class='title'>{$lang['AVAILABLE']}</div> <div class='available'>{$v}</div></button></li>";
					}
				}
				else if ( substr($k,0,1) == 'c')
				{
					if ( $v == 0 )
					{
						$html .= "
						<li><button class='bookButton disabled'>
							<div class='title' disabled='disabled'>{$lang['AVAILABLE']}</div>
							<div class='available' disabled='disabled'>0</div>
						</button></li>";
					}
					else
					{
						$html .= "
						<li><button class='bookButton orange' onclick=\"reservation('".substr($k,2)."', '{$name}', '{$arena}', '{$node->slot[$r]['date_time']}', 'ACCEPT', '{$numChall}', '{$contact}', this, {$smsM}, {$smsC});return false;\"> <div class='title'>{$lang['AVAILABLE']}</div> <div class='available'>{$v}</div></button></li>";
					}
				}
			}
			
			$html .= "
			</ul>";
		}
		// end of bottom table
		$html .= "
		</div>
		<br style='clear:both;'/>
		<hr width='516px' style='margin:15px 0px 15px 100px;'/>"; 
*/
	}
	
	$html .= "
	</section>";
	
	$database->__destruct(); unset($database);
	$fp = fopen("../../search_results/results.html", 'w'); fwrite($fp, $html); fclose($fp);
	
	echo $html;
?>