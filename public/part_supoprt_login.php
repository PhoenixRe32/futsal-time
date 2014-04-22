<?php
$div_login_html;
$div_login_message_html;

if ( !isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn'] )
{
	$div_login_html = "<div id='login'>";
	$div_login_message_html = "<div id='login_message' style='display:none;'></div>";
}
else
{
	$div_login_html = "<div id='login' style='display:none' >";
	$div_login_message_html = "<div id='login_message' class='success'>
								<span style='display:block'>{$_SESSION['name']}</span>
								<span style='display:block'>{$_SESSION['email']}</span>
								<span style='display:block'></span>
								<hr style='width:50%'/>
								<span style='display:block'>
									<a href='index.php?caller=logout' id='logout'><i><b><u>Logout</u></b></i></a>
								</span>
								</div>";
}
?>
<table id='login_box_frame' border="0" width='160px' style="margin:15px 0px 0px 5px;">
<tr>
	<td >
	<div id="login_box">
		<!-- LOGIN FORM CODE START -->
		<div id='login_form'>
			<?php echo $div_login_html; ?>
				<fieldset>
				<legend><?php echo $lang['LOGIN_FORM']; ?></legend>
				
				<input type='hidden' name='login_form_focused' id='login_form_focused' value='0'/>
					
				<div class='container'>
					<label for='login_email' ><?php echo $lang['EMAIL_ADDRESS']; ?>:</label><br/>
					<input type='text' name='login_email' id='login_email' value='' maxlength="40" /><br/>
					<span id='login_email_errorloc' class='error'></span>
				</div>
						
				<div class='container'>
					<label for='login_password' ><?php echo $lang['PSW']; ?>:</label><br/>
					<input type='password' name='login_password' id='login_password' maxlength="40" />
					<div id='login_password_errorloc' class='error'></div>
				</div>
						
				<div class='container'>
					<input type="button" id="loginSubmit" name="login" value='<?php echo $lang['LOGIN'];?>' style="float:right;" />
					<input type="button" id="activator" name="register_activator" value='<?php echo $lang['REGISTER']; ?>' style="float:left;" />
				</div>
			</fieldset>
			</div>
		</div>
		<!-- LOGIN FORM CODE END -->
		<div class='container' style='text-align:center;'>
			<?php echo $div_login_message_html; ?>
		</div>
	</div>
	</td>
</tr>

<tr>
	<td>
		<div id='lang_box' class='container' style='text-align:center;'>
			<img src="./img/cy.png" alt="<?php echo $lang['GREEK']; ?>" style='cursor:pointer;' width='32px' onclick="setLang('gr')">
			<img src="./img/uk.png" alt="<?php echo $lang['ENGLISH']; ?>" style='cursor:pointer;' width='32px' onclick="setLang('en')">
		</div>
	</td>
</tr>
</table>