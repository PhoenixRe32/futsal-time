<?php
	// Set redirection page; current page he is or if not set for some reason the home page.
	$redirect = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?caller=index';
	
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
	
	// Redirect user
	header("Location: $redirect");
	exit();
?>