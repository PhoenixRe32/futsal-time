<?php
	include_once(dirname(__FILE__)."/scripts/php/levelcontrol.php");
	// require_once '../Mobile-Detect-2.6.9/Mobile_Detect.php';
	
	// $detect = new Mobile_Detect;
	// $mobile = $detect->isMobile();
	// $mobile = true;
	//echo 'start'; var_dump($_SESSION); exit();
	if ( !isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn'] )
	{
		// echo 'logged'; var_dump($_SESSION); exit();
		include_once(dirname(__FILE__)."/scripts/php/logout.php");
	}	
	if ( !isset($_GET['caller']) || empty($_GET['caller']) )  
	{
		// echo 'caller'; var_dump($_SESSION); exit();
		$redirect = 'http://'.$_SERVER['HTTP_HOST'].'/view.php?caller=calendar';
		// if ( $mobile ) $redirect .= '&m=1';
		header("Location: $redirect");
		exit();
	}
	else if ( $_GET['caller'] == 'logout' ) 
	{
		include_once(dirname(__FILE__)."/scripts/php/logout.php");
	}
	// var_dump($_SESSION);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Manager</title>
	<link rel="stylesheet" type="text/css" href="./css/futsal.css">								<!-- GENERAL CSS FILES -->
	<script type="text/javascript" src="./scripts/js/jquery-1.7.2.min.js"></script>				<!-- JQUERY -->
	<script type="text/javascript" src="./scripts/js/jquery-ui-1.10.2.custom.min.js"></script>	<!-- JQUERY UI -->
	<script type='text/javascript'>
		var schedule_width;
		$(document).ready(function() {
			schedule_width = $(window).width()-15-60; 
			$('#include_xxx_part').width(schedule_width);
		});
	</script>
	<link rel="stylesheet" type="text/css" href="./css/jquery-ui-1.10.2.custom.min.f.css"/>		<!-- JQUERY UI CSS -->
	<script type="text/javascript" src="./scripts/js/css_browser_selector.js"></script>			<!-- BROWSER DETECTION -->
	<link rel="stylesheet" type="text/css" href="./css/reservations.css" />						<!-- RESERVE FORM CSS-->
	<link rel="stylesheet" type="text/css" href="./css/messagebox.css"/>						<!-- MESSAGE BOX CSS -->
	<link rel="stylesheet" type="text/css" href="./css/settings.css"/>							<!-- TABLES CSS -->
</head>

<body>

<div id="Page">
	<div id="Content" style="margin-left:10px;">
		<div id='include_navigation_part'>
			<?php include_once(dirname(__FILE__)."/part_support_navigation.php"); ?>
		</div> <!-- include_navigation_part -->
		<div id='include_xxx_part' class="mainPage" style='min-height:400px; min-width:800px; float:left;'>
			<?php 
			switch( $_GET['caller'] )
			{
				case 'calendar' : 
					include_once(dirname(__FILE__)."/part_main_calendar.php"); 
					break;
				case 'settings' : 
					include_once(dirname(__FILE__)."/part_main_settings.php"); 
					break;
				case 'members' : 
					include_once(dirname(__FILE__)."/part_main_member.php"); 
					break;
				case 'tools' : 
					include_once(dirname(__FILE__)."/part_main_tools.php"); 
					break;
				default :
					include_once(dirname(__FILE__)."/part_main_error.php"); 
					break;
			}		
		?>		
		</div>
		
	</div> <!-- Content -->

</div><!--end of content-->

<script type="text/javascript" src="./scripts/js/messagebox.js"></script>		<!-- MESSAGE BOX JAVASCRIPT CODE -->
</body>
</html>