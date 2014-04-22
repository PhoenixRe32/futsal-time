<?php
	$links = array();
	$links['calendar'] = ( $_GET['caller'] == 'calendar' ) ? "<li><a id='onlink' href='view.php?caller=calendar'>Schedule</a></li>" : "<li><a href='view.php?caller=calendar'>Schedule</a></li>";
	$links['settings'] = ( $_GET['caller'] == 'settings' ) ? "<li><a id='onlink' href='view.php?caller=settings'>Settings</a></li>" : "<li><a href='view.php?caller=settings'>Settings</a></li>"; 
	$links['members'] = ( $_GET['caller'] == 'members' ) ? "<li><a id='onlink' href='view.php?caller=members'>Members</a></li>" : "<li><a href='view.php?caller=members'>Members</a></li>";
	$links['tools'] = ( $_GET['caller'] == 'tools' ) ? "<li><a id='onlink' href='view.php?caller=tools'>Tools</a></li>" : "<li><a href='view.php?caller=tools'>Tools</a></li>";
	$links['logout'] = ( $_GET['caller'] == 'logout' ) ? "<li><a id='onlink' href='view.php?caller=logout'>Logout</a></li>" : "<li><a href='view.php?caller=logout'>Logout</a></li>";
?>

<div>
	<div id="navbar">
		<div id="holder">
			<ul> 
				<?php foreach ( $links as $link ) echo $link; ?>
			</ul> 
		</div>
	</div>
</div>