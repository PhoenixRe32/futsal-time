<?php
include_once(dirname(__FILE__)."/scripts/php/levelcontrol.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Home</title>
	<script type='text/javascript' src='./scripts/js/jquery-1.7.2.min.js'></script>		<!-- JQUERY -->
	<script type="text/javascript" src="./scripts/js/jstorage.min.js"></script>			<!-- STORAGE -->
	<link rel="stylesheet" type="text/css" href="./css/messagebox.css"/>				<!-- MESSAGE BOX CSS -->
</head>

<body style=" margin-top:50px;">

<div class="bubble">
	<div title="Opening Image" style="position:relative; text-align:center; padding-top:20px; margin-bottom:20px;">
		<img src="images/bannerManager.png" alt="banner" />
	</div>

	<div style="position:relative; text-align:center;">
		<div id='login_form'>
			<input type='hidden' name='login_form_focused' id='login_form_focused' value='0'/>
			<label for="login_arena"> &nbsp &nbsp &nbsp Arena: </label>
				<input type="text" name="login_arena" id="login_arena" size="24" />
			<br /> <br />
			<label for="login_name"> Username: </label>
				<input type="text" name="login_name" id="login_name" size="24" />
			<br /> <br />
			<label for="login_password"> Password: </label>
				<input type="password" name="login_password" id="login_password" size="24" />
			<br /> <br />
			<input type="button" name="loginSubmit" id="loginSubmit" value="Log In" />
		</div>
	</div>
</div>

<script type="text/javascript" src="./scripts/js/login.js"></script>			<!-- LOGIN VALIDATION AND POST JAVASCRIPT CODE AND KEY ASSOCIATION-->
<script type="text/javascript" src="./scripts/js/messagebox.js"></script>		<!-- MESSAGE BOX JAVASCRIPT CODE -->

</body>
</html>