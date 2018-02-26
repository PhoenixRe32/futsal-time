<!-------------------------------------------------------------------------------------------------------------------->
<!-- THE OVERLAY AND THE REGISTRATION BOX START -->
<!--
		HTML borrowed by the "CSS and jQuery Tutorial: Overlay with Slide Out Box" tutorial
		by Mary Lou (http://tympanus.net/codrops/author/crnacura/)
		at codrops (http://tympanus.net/codrops/2009/12/03/css-and-jquery-tutorial-overlay-with-slide-out-box/).
		Credit where credit is due. Our thanks.
-->
<div class="register_box" id="register_box" >
	<a class="boxclose" id="register_close" tabindex='500'></a>
	<!-- REGISTER FORM CODE START -->
	<div id='register_form'>	
		<fieldset>
			<legend><?php echo $lang['REGISTRATION_FORM']; ?></legend>

			<input type='hidden' name='register_form_visible' id='register_form_visible' value='0'/>
			<input type='hidden' name='equVal' id='equVal' value=''/>
			
			<div class='short_explanation'>* <?php echo $lang['REQ']; ?></div>

			<div class='container'>
				<label for='name' ><?php echo $lang['FULL_NAME']; ?>*: </label><br/>
				<input type='text' name='name' id='name' value='' maxlength="50" tabindex='510'/><br/>
				<span id='register_name_errorloc' class='error'></span>
			</div>
			
			<div class='container'>
				<label for='email' ><?php echo $lang['EMAIL_ADDRESS']; ?>*:</label><br/>
				<input type='text' name='email' id='email' value='' maxlength="50" tabindex='520'/><br/>
				<span id='register_email_errorloc' class='error'></span>
			</div>
			
			<div class='container'>
				<label for='email' ><?php echo $lang['RE_EMAIL_ADDRESS']; ?>*:</label><br/>
				<input type='text' name='emailAgain' id='emailAgain' value='' maxlength="50" tabindex='525'/><br/>
				<span id='register_emailAgain_errorloc' class='error'></span>
			</div>
			
			<div class='container'>
				<label for='phone' ><?php echo $lang['CONTACT_PHONE']; ?>*:</label><br/>
				<input type='text' name='phone' id='phone' value='' maxlength="50" tabindex='530'/><br/>
				<span id='register_phone_errorloc' class='error'></span>
			</div>
			
			<div class='container'>
				<label for='email' ><?php echo $lang['RE_CONTACT_PHONE']; ?>*:</label><br/>
				<input type='text' name='phoneAgain' id='phoneAgain' value='' maxlength="50" tabindex='535'/><br/>
				<span id='register_phoneAgain_errorloc' class='error'></span>
			</div>
			
			<div class='container' style='position:absolute; top:-1000px;'>
				<label for='securityQuestion' >What is your mother's birth town *:</label><br/>
				<input type='text' name='securityQuestion' id='securityQuestion' value='' maxlength="50" tabindex='0'/><br/>
				<span id='register_securityQuestionerrorloc' class='error'></span>
			</div>
			
			<div class='container'>
				<label for='password' ><?php echo $lang['PSW']; ?>*:</label><br/>
				<div class='pwdwidgetdiv' id='thepwddiv' ></div>
				<noscript>
					<input type='password' name='password' id='password_id' maxlength="50" tabindex='540'/>
				</noscript>   
				<span id='register_password_errorloc' class='error'></span>
			</div>
			<div class='container' style='clear:both;'>
				<label for='humanVerification' ><?php echo $lang['SECURITY_Q']; ?> <span id='var1'></span><span id='oper'></span><span id='var2'></span> *:</label><br/>
				<input type='text' name='humanVerification' id='humanVerification' value='' maxlength="50" tabindex='545'/><br/>
				<span id='register_humanVerification_errorloc' class='error'></span>
			</div>
			<div class='short_explanation'><?php echo $lang['REG_VER']; ?> <a href='./termsandconditions.html' target='_blank' onClick='showPopup(this.href);return(false);'><?php echo $lang['T_C']; ?>.</a></div>
			
			<div class='container'>
				<input type='button' id='registerSubmit' name='submit' value='<?php echo $lang['REGISTER']; ?>' tabindex='570'/>
			</div>

		</fieldset>
	</div>
	<!-- REGISTER FORM CODE END -->
</div>
<!-- THE OVERLAY AND THE REGISTRATION BOX END -->
<!-------------------------------------------------------------------------------------------------------------------->
<script type="text/javascript">
function showPopup(url) {
	newwindow=window.open(url,'name','height=320,width=640,top=100,left=300,resizable');
	if (window.focus) { newwindow.focus(); }
}
</script>