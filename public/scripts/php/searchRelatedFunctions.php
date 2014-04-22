<?php
include_once(dirname(__FILE__)."/databaseClass.php");

function arenaFreeSlotSearch($database, $date, $hour, $nick, $slotFormat, $doublesInfo, $sizesInfo)
{
	$dateNTime = DateTime::createFromFormat('Y-m-d H:i:s', $date.' '.$hour);
	$interval;
	switch ( $slotFormat )
	{
		case '15': $interval = new DateInterval("PT15M"); break;
		case '30': $interval = new DateInterval("PT30M"); break;
		case '60': $interval = new DateInterval("PT60M"); break;
		default  : $interval = new DateInterval("PT15M"); break;
	}
	
	for ( $i = 0; $i < 4; $i++ )
		$dateNTime->sub($interval);

	$xml = "
	<arena nick='{$nick}'>";
	
	for ( $i = 0; $i < 10; $i++)
	{
		$dateNTimeString = $dateNTime->format('Y-m-d H:i:s');		
		
		$statement = "
			SELECT id, date, time, field, game, status, fieldSize
			FROM game_slots_{$nick}
			INNER JOIN fields_{$nick} ON field = fieldId
			WHERE date=DATE('{$dateNTimeString}') AND time=TIME('{$dateNTimeString}');";
		$slotSet = $database->fetchSet($statement);
		if ( $database->getErrorStatus() )
		{
			$database->closeConnection();
			break;
		}
		
		if ( empty($slotSet) )
		{
			$xml .= "
		<slot date_time='{$dateNTimeString}'>
		</slot>";
		}
		else
		{
			$statement = "SELECT DISTINCT fieldSize FROM fields_{$nick} WHERE type='S' ORDER BY fieldSize ASC;";
			$sizelist = $database->fetchSet($statement);
			if ( $database->getErrorStatus() )
			{
				$xml .= "
		<slot date_time='{$dateNTimeString}'>
		</slot>";
				$database->closeConnection();
				break;
			}
		
			$free = array();
			$challenges = array();
			$doubles = array();
			foreach ( $sizelist as $s )
			{
				$free[$s['fieldSize']] = 0;
				$challenges[$s['fieldSize']] = 0;
			}
			unset($sizelist);
			foreach ( $sizesInfo as $s )
			{
				$doubles[$s] = 0;
			}
			
			foreach ( $slotSet as $slot )
			{
				if ( $slot['game'] == 'N' && $slot['status'] == 'E' )
					$free[$slot['fieldSize']]++;
				else if ( $slot['game'] == 'C' && $slot['status'] == 'E' )
				{
					$free[$slot['fieldSize']]++;
					$challenges[$slot['fieldSize']]++;
				}
			}
			foreach ( $doublesInfo as $k => $double )
			{
				$df = count($double);
				foreach ( $slotSet as $slot )
				{
					if ( in_array($slot['field'], $double) && ( $slot['game'] == 'N' || $slot['game'] == 'C') && $slot['status'] == 'E' )
						$df--;
				}
				if ( $df == 0 )
					$doubles[$sizesInfo[$k]]++;
			
			}
			$ch = 0;
			$xml .= "
		<slot date_time='{$dateNTimeString}'>
			";
			foreach ( $free as $k => $v )
			{
				$xml .= "<f_{$k}>{$v}</f_{$k}>
			";
			}
			foreach ( $doubles as $k => $v )
			{
				$xml .= "<d_{$k}>{$v}</d_{$k}>
			";
			}
			foreach ( $challenges as $k => $v )
			{
				$xml .= "<c_{$k}>{$v}</c_{$k}>
			";
				$ch += $v;
			}
			$xml .= "<challenges>{$ch}</challenges>
		</slot>";
		}
		$dateNTime->add($interval);
	}
	
	$xml .= "
	</arena>";
	return $xml;
}
?>