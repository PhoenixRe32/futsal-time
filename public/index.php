<?php
include_once(dirname(__FILE__)."/scripts/php/levelcontrol.php");

	if ( !isset($_GET['caller']) || empty($_GET['caller']) )
	{
		$redirect = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?caller=booking';
		header("Location: $redirect");
		exit();
	}
	else if ( $_GET['caller'] == 'logout' ) 
	{
		include_once(dirname(__FILE__)."/scripts/php/logout.php");
	}
	else if ( $_GET['caller'] == 'member' && ( !isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn'] || 
												!isset($_SESSION['initiated']) || !$_SESSION['initiated'] ) )
	{
		$redirect = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?caller=index';
		header("Location: $redirect");
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Book Now</title>
	<script type="text/javascript" src="./scripts/js/setLang.js"></script>				<!-- SELECT LANGUAGE -->
	<script type="text/javascript" src="./scripts/js/jquery-1.7.2.min.js"></script>		<!-- JQUERY -->
	<script type="text/javascript" src="./scripts/js/jstorage.min.js"></script>			<!-- STORAGE -->
	<script type="text/javascript" src="./scripts/js/css_browser_selector.js"></script>	<!-- BROWSER DETECTION -->
	<link rel="stylesheet" type="text/css" href="./css/futsal.css">						<!-- GENERAL CSS FILES -->
	<link rel="stylesheet" type="text/css" href="./css/buttons.css" />					<!-- BUTTON CSS FILES -->
	<link rel="stylesheet" type="text/css" href="./css/pwdwidget.css"/>					<!-- PASSWORD SECTION CSS -->
	<link rel="stylesheet" type="text/css" href="./css/register.css"/>					<!-- REGISTER FORM CSS -->
	<link rel="stylesheet" type="text/css" href="./css/reset.css"/>						<!-- RESET FORM CSS -->
	<link rel="stylesheet" type="text/css" href="./css/overlay.css"/>					<!-- OVERLAY AND FORMS CSS -->
	<link rel="stylesheet" type="text/css" href="./css/messagebox.css"/>				<!-- MESSAGE BOX CSS -->
	<link rel="stylesheet" type="text/css" href="./css/login.css" />					<!-- LOGIN FORM CSS -->
	<link rel="stylesheet" type="text/css" href="./css/jmNotify.css" />					<!-- NOTIFICATIONS -->	
	<link rel="stylesheet" type="text/css" href="./css/search.css"/>					<!-- SEARCH SECTION CSS-->
	<link rel="stylesheet" type="text/css" href="./css/reservations.css" />				<!-- RESERVE FORM CSS-->
	<link rel="stylesheet" type="text/css" href="./css/bookhistory.css" />				<!-- BOOKING NAVIGATION CSS -->
	<link rel="shortcut icon" href="favicon.ico"/>
    
    <!--Google Analytics-->
    <script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-45029158-1', 'futsalthoi.com');
	  ga('send', 'pageview');
	</script>

</head>

<body>
<?php
	// foreach ( $_SESSION as $k => $v )
// {
    // echo '<br>'.$k.' => '.$v;
// }
?>
<div class="overlay" id="overlay" style="display:none;"></div>

<div id='include_registration_part' style='height:0px;'> 
	<?php include_once(dirname(__FILE__)."/part_support_registration_empty.php"); ?>
</div>

<div id='include_reset_part' style='height:0px;'>
	<?php include_once(dirname(__FILE__)."/part_support_reset_password_empty.php"); ?>
</div>

<div id='login_things' style='height:0px;'>
	<?php include_once(dirname(__FILE__)."/part_supoprt_login_empty.php"); ?>
</div>

<div id="Page">
	<div id='include_banner_part'>
		<?php include_once(dirname(__FILE__)."/part_support_banner.php"); ?>
	</div>

	<div id="Content" style="margin-left:20px; float:left;">
		<div id='include_navigation_part'>
			<?php include_once(dirname(__FILE__)."/part_support_navigation.php"); ?>
		</div> <!-- include_navigation_part -->
		
		<div id='include_xxx_part' class="mainPage" style='width:860px; padding-right:20px; min-height:800px; padding-bottom:20px;'>
		<?php 
			switch( $_GET['caller'] )
			{
				case 'index' : 
					include_once(dirname(__FILE__)."/part_main_search.php");  //include_once(dirname(__FILE__)."/part_main_index.php"); 
					break;
				case 'booking' : 
					include_once(dirname(__FILE__)."/part_main_search.php"); 
					break;
				case 'member' : 
					include_once(dirname(__FILE__)."/part_main_member.php"); 
					break;
				case 'information' : 
					include_once(dirname(__FILE__)."/part_main_information.php"); 
					break;
				default :
					include_once(dirname(__FILE__)."/part_main_error.php"); 
					break;
			}		
		?>		
		</div>
	</div> <!-- Content -->

	<div id='include_useful_part' style='float:left; margin-left:20px; position:relative; margin-top:103px; '>
		<?php include_once(dirname(__FILE__)."/part_support_side.php"); ?>
	</div>
		
	<div id='definition' style='margin:6px 0px 0px 107px; width:735px; font-size:small; text-align:center;'>
		<?php include_once(dirname(__FILE__)."/part_support_definition.php"); ?>
	</div>

</div><!--end of content-->

<script type="text/javascript" src="./scripts/js/login.js"></script>				<!-- LOGIN VALIDATION AND POST JAVASCRIPT CODE AND KEY ASSOCIATION-->
<script type="text/javascript" src="./scripts/js/register.js"></script>				<!-- REGISTER FORM VALIDATION AND KEY ASSOCIATION -->
<script type="text/javascript" src="./scripts/js/pwdwidget.js"></script>			<!-- PASSWORD JAVASCRIPT CODE -->
<script type="text/javascript" src="./scripts/js/resetPassword.js"></script>		<!-- RESET FORM JAVASCRIPT -->
<script type="text/javascript" src="./scripts/js/resendValidationEmail.js"></script>
<script type="text/javascript" src="./scripts/js/messagebox.js"></script>			<!-- MESSAGE BOX JAVASCRIPT CODE -->
<script type="text/javascript" src="./scripts/js/jquery.jmNotify.js"></script>		<!-- NOTIFICATIONS -->

</body>
</html>