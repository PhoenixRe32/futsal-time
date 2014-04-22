<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/misc.php");
		
	function arenaExists($database, $name)
	{
		$statement = "
			SELECT	id 
			FROM	arenas
			WHERE	name='{$name}'";
		$arenaID = $database->fetchCell($statement);

		if ( $database->getErrorStatus() ) { return false; }
		
		if ( empty($arenaID) ) return false;
		else return $arenaID;
	}

	function nameExists($database, $name, $arenaID)
	{
		$statement = "
			SELECT	id 
			FROM	managers
			WHERE	name='{$name}' AND arena={$arenaID};";
		$manID = $database->fetchCell($statement);
		if ( $database->getErrorStatus() ) { return false; }
		
		if ( empty($manID) ) return false;
		else return $manID;
	}

	function passwordMatches($database, $id, $arena, $name, $password)
	{
		$hash = crypt($password, '$6$rounds=8888$'.$arena.$name.'$');
		$statement = "
			SELECT	phones as p, bookingLimit as bl, 
					smsContact as smsP, smsCustomer as smsC, smsManager as smsM, 
					slotFormatClient as sfC, slotFormatManager as sfM, refreshRate as rr
			FROM	managers
			WHERE	id='{$id}' AND password='$hash';";
		$permissions = $database->fetchRow($statement); //var_dump($statement);
		if ( $database->getErrorStatus() ) { return null; } //echo $statement; var_dump($numArena);

		if ( empty($permissions) ) return null;
		else
		{
			$statement = "
				SELECT	arenas.id, name, nick, phones, bookingLimit,
						slotFormatClient, slotFormatManager, refreshRate, 
						smsContact, smsManager, smsCustomer
				FROM	arenas
				INNER JOIN arenaSettings ON arenas.id = arenaSettings.id
				WHERE	arenas.id={$arena};";
			$arenaInfo = $database->fetchRow($statement); //var_dump($statement);
			if ( $database->getErrorStatus() ) { return null; }
			
			if ( empty($arenaInfo) ) return null;
			else return array_merge($arenaInfo, $permissions);
		}
	}
	
	function phoneExists($database, $phone)
	{
		$statement = "
			SELECT COUNT(*) 
			FROM customers
			WHERE phone='{$phone}'";
		$numPhone = $database->fetchCell($statement);
		if ( $database->getErrorStatus() ) { return false; }
		
		if ( $numPhone == 0 ) return false;
		else if ( $numPhone == 1 ) return true;
		else { $database->errorManagement("arenaRelatedFnctions.php // phoneExists/n{$numPhone} phones found", 'Impossible number of phones', $phone); return true; }
	}
	
	function addUnregUser($database, $name, $phone)
	{
		$valpsw = generatePassword(16);
		$statement = "
			INSERT INTO customers(email, name, phone, validated) VALUES
			('', '{$name}', '{$phone}', '$valpsw')";
		$database->runQuery($statement);
	}	
	
	function getSMSTimes($database, $arenaID)
	{
		$statement = "
			SELECT	*
			FROM	smsTimes
			WHERE	arena={$arenaID}
			ORDER BY startAt ASC";
		$timesSet = $database->fetchSet($statement);
		if ( $database->getErrorStatus() ) return null;
		else return $timesSet;
	}
?>