<?php

$file = './populate.sql';
$dateNTime = DateTime::createFromFormat('Y-m-d H:i:s', '2014-10-26 00:00:00');
$iterDateNTime = DateTime::createFromFormat('Y-m-d H:i:s', '2014-10-26 00:00:00');
$interval_1 = new DateInterval("PT15M");
$interval_14 = new DateInterval("PT17H");
$fields = 7;

	try 
	{
		$fp = fopen($file, 'w');
	}
	catch (Exception $e) {
		echo $e->getMessage();
		error("File could not be opened");
	}

	$content = "";
	while ( $iterDateNTime->diff($dateNTime)->format('%a') <= 636 ) 
	{
		for ($i = 7; $i <= $fields; $i++) {
			if($iterDateNTime->format('H:i:s')<"18:00:00"){
				$content = "
					INSERT INTO game_slots_thoi 
					(date,time,field,game) VALUES 
					('{$iterDateNTime->format('Y-m-d')}', '{$iterDateNTime->format('H:i:s')}',{$i},'U');";
			}
			else
			{
				$content = "
					INSERT INTO game_slots_thoi
					(date,time,field,game) VALUES 
					('{$iterDateNTime->format('Y-m-d')}', '{$iterDateNTime->format('H:i:s')}',{$i},'N');";
			}	
			fwrite($fp, $content);
			echo $content;
			echo '<br />';
		}
		$iterDateNTime->add($interval_1)->format('Y-m-d H:i:s');
	}
	fclose($fp);
?>