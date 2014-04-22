<?php
	// Start session
	session_start();

	// Check whether the session variable initiated is present or not. If NOT then regenerate the id of the session
	// to prevent the chance of a compromised session id.
	if ( !isset($_SESSION['initiated']) || !$_SESSION['initiated'] )
	{
		session_regenerate_id(true);
		$_SESSION['initiated'] = true;
		$_SESSION['loggedIn'] = false;
		$_SESSION['bd'] = time();
	}
	else
	{	
		// If it is then it is the 'current' user so just increase the clicks and if he is logged in then save the
		// location he has navigated to. 
		if ( $_SESSION['loggedIn'] )
		{
			if ( $_SESSION['type'] == 'customer' )
			{
				;
			}
			else
			{
				// Delete cookie
				setcookie (session_id(), "", 1);
				
				// Destroy session and session variables;
				$_SESSION = array();
				session_destroy();
				
				// Start a new session
				session_start();
				session_regenerate_id(true);
				$_SESSION['initiated'] = true;
				$_SESSION['loggedIn'] = false;
				$_SESSION['bd'] = time();
			}
		}
	}

	if ( isset($_COOKIE['language']) )
	{
		$_SESSION['language'] = $_COOKIE['language'];
	}
	else
	{
		$_SESSION['language'] = 'gr';
		setcookie('language', 'gr', time() + (3600 * 24 * 365)); 
	}
	
	include_once(dirname(__FILE__)."/languages/lang.{$_SESSION['language']}.php");
?>