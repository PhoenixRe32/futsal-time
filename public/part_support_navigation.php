<?php
	$links = array();
	//$links['index'] = ( $_GET['caller'] == 'index' ) ? "<li><a id='onlink' href='index.php?caller=index'>{$lang['TAB_HOME']}</a></li>" : "<li><a href='index.php?caller=index'>{$lang['TAB_HOME']}</a></li>";
	$links['booking'] = ( $_GET['caller'] == 'booking' || $_GET['caller'] == 'index' ) ? "<li><a id='onlink' href='index.php?caller=booking'>{$lang['TAB_BOOK']}</a></li>" : "<li><a href='index.php?caller=booking'>{$lang['TAB_BOOK']}</a></li>"; 
	$links['information'] = ( $_GET['caller'] == 'information' ) ? "<li><a id='onlink' href='index.php?caller=information'>{$lang['TAB_INFO']}</a></li>" : "<li><a href='index.php?caller=information'>{$lang['TAB_INFO']}</a></li>";
	$links['member'] = ( $_GET['caller'] == 'member' ) ? "<li id='userLink'><a id='onlink' href='index.php?caller=member'>{$lang['TAB_MEMBER']}</a></li>" : "<li id='userLink'><a href='index.php?caller=member'>{$lang['TAB_MEMBER']}</a></li>";
	if ( !isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn'] ) $links['member'] = "<li id='userLink' style='display:none'><a href='index.php?caller=member'>{$lang['TAB_MEMBER']}</a></li>";
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