<?php
	// Start session
	session_start();

	// Check whether the session variable initiated is present or not. If NOT then regenerate the id of the session
	// to prevent the chance of a compromised session id.
	if ( !isset($_SESSION['initiated']) )
	{
		session_regenerate_id(true);
		$_SESSION['initiated'] = true;
		$_SESSION['loggedIn'] = false;
		$_SESSION['bd'] = time();
	}
	else
	{	
		if ( $_SESSION['loggedIn'] )
		{
			if ( $_SESSION['type'] == 'manager' )
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
			}
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
		}
	}
?>