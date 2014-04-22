<?php

$file = './populate.sql';
$dateNTime = DateTime::createFromFormat('Y-m-d H:i:s', '2013-09-03 00:00:00');
$iterDateNTime = DateTime::createFromFormat('Y-m-d H:i:s', '2013-09-03 00:00:00');
$interval_1 = new DateInterval("PT15M");
$interval_14 = new DateInterval("PT17H");
$fields[0][0] = 'game_slots_camp1';
$fields[0][1] = 6;
$fields[1][0] = 'game_slots_nuca';
$fields[1][1] = 8;
$fields[2][0] = 'game_slots_paeek';
$fields[2][1] = 3;
$fields[3][0] = 'game_slots_thoi';
$fields[3][1] = 3;

	try 
	{
		$fp = fopen($file, 'w');
	}
	catch (Exception $e) {
		echo $e->getMessage();
		error("File could not be opened");
	}

	$content = "";
	while ( $iterDateNTime->diff($dateNTime)->format('%a') <= 31 ) 
	{
		for ($i = 0; $i <= count($fields); $i++) {
			for ($j = 1; $j <= $fields[$i][1]; $j++) {
				if($iterDateNTime->format('H:i:s')<"17:00:00"){
					$content = "
						INSERT INTO {$fields[$i][0]}
						(date,time,field,game) VALUES 
						('{$iterDateNTime->format('Y-m-d')}', '{$iterDateNTime->format('H:i:s')}',{$j},'U');";
				}
				else
				{
					$content = "
						INSERT INTO {$fields[0]}
						(date,time,field,game) VALUES 
						('{$iterDateNTime->format('Y-m-d')}', '{$iterDateNTime->format('H:i:s')}',{$j},'N');";
				}	
				fwrite($fp, $content);
				echo $content;
				echo '<br />';
			}
		}
		$iterDateNTime->add($interval_1)->format('Y-m-d H:i:s');
	}
	fclose($fp);
?>