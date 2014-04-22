<?php
	include_once(dirname(__FILE__)."/databaseClass.php");
	include_once(dirname(__FILE__)."/misc.php");

	function emailExists($database, $email)
	{
		$statement = "
			SELECT COUNT(*) 
			FROM customers
			WHERE email='{$email}'";
		$numEmail = $database->fetchCell($statement);
		if ( $database->getErrorStatus() ) { return false; }
		
		if ( $numEmail == 0 ) return false;
		else if ( $numEmail == 1 ) return true;
		else { $database->errorManagement("userRelatedFnctions.php // emailExists/n{$numEmail} emails found", 'Impossible number of emails', $email); return true; }
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
		else { $database->errorManagement("userRelatedFnctions.php // phoneExists/n{$numPhone} phones found", 'Impossible number of phones', $phone); return true; }
	}
	
	function regUserExists($database, $phone)
	{
		$statement = "
			SELECT COUNT(*) 
			FROM customers
			WHERE phone='{$phone}' AND validated='VALIDATED'";
		$numCustomer = $database->fetchCell($statement);
		if ( $database->getErrorStatus() ) { return false; }
		
		if ( $numCustomer == 0 ) return false;
		else if ( $numCustomer == 1 ) return true;
		else { $database->errorManagement("userRelatedFnctions.php // regUserExists/n{$numCustomer} customers found", 'Impossible number of customers', $phone); return true; }
	}

	function passwordMatches($database, $email, $password)
	{
		$hash = crypt($password, '$6$rounds=8888$'.$email.'$');
		
		$statement = "
			SELECT COUNT(*)
			FROM customers
			WHERE email='{$email}' AND password='$hash'";
		$numCustomer = $database->fetchCell($statement);
		if ( $database->getErrorStatus() ) { return null; }
		
		if ( $numCustomer == 1 )
		{
			$statement = "
				SELECT email, name, phone, alertChallenge, validated
				FROM customers
				WHERE email='{$email}' AND password='{$hash}'";
			$customerInfo = $database->fetchRow($statement);
			if ( $database->getErrorStatus() ) { return null; }
			$customerInfo['validated'] = ( $customerInfo['validated'] == 'VALIDATED' ) ? true : false;
			return $customerInfo;
		}
		else if ( $numCustomer == 0 ) { return null; }
		else { $database->errorManagement("userRelatedFnctions.php // passwordMatches/n{$numCustomer} customers found", 'Impossible number of customers', $email); return null; }
	}

	function addUser($database, $email, $name, $phone, $password, $randomPassword)
	{
		$hash = crypt($password, '$6$rounds=8888$'.$email.'$');
		
		$statement = "
			INSERT INTO customers (email, name, phone, password, alertChallenge, validated) VALUES
			('{$email}', '{$name}', '{$phone}', '{$hash}', FALSE, '{$randomPassword}')";
		$database->runQuery($statement);
		
		return (!$database->getErrorStatus());
	}
	
	function updateUser($database, $email, $name, $phone, $password, $notify)
	{
		$hash = crypt($password, '$6$rounds=8888$'.$email.'$');
		
		$statement = "
			UPDATE customers
			SET email = '{$email}', name = '{$name}', password = '{$hash}', alertChallenge = {$notify}
			WHERE phone = '{$phone}';";
		$database->runQuery($statement);
		
		return (!$database->getErrorStatus());
	}

	function updateUserLostValidation($database, $email, $psw, $validationCode)
	{
		$hash = crypt($psw, '$6$rounds=8888$'.$email.'$');
		
		$statement = "
			UPDATE customers
			SET validated = '{$validationCode}', password = '{$hash}'
			WHERE email = '{$email}';";
		$database->runQuery($statement);
		
		return (!$database->getErrorStatus());
	}
	
	function updatePhone($database, $email, $name, $password, $notify, $oldPhone)
	{
		$hash = crypt($password, '$6$rounds=8888$'.$email.'$');
		
		$statement = "
			UPDATE customers
			SET name = '{$name}', password = '{$hash}', alertChallenge = '{$notify}'
			WHERE phone = '{$oldPhone}' AND email = '{$email}' ;";/*email changed from set to AND***************************/
		$database->runQuery($statement);
		
		return (!$database->getErrorStatus());
	}

	function fetchUserSchedule($database, $phone)
	{
		$phone = trim($phone);
		if ( empty($phone) )
			return array();
			
		$statement = "
				SELECT	reservations.id, reservations.arena, reservations.date, reservations.time, reservations.gameType,
						reservations.duration, reservations.customer, reservations.opponent, reservations.fieldSize, arenas.name
				FROM 	reservations
				INNER JOIN arenas ON arenas.nick = reservations.arena
				WHERE	(customer='{$phone}' OR opponent='{$phone}')
				ORDER BY date DESC, time DESC;";// set interval 0 for local and 7 for host
		$bookings = $database->fetchSet($statement);
		
		return $bookings;
	}
?>