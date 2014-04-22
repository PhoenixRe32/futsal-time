<div class="reset_box" id="reset_box" style='display:none'>
	<a class="boxclose" id="reset_close" tabindex='500'></a>
	<div id='reset_form'>
		<fieldset>
			<legend><?php echo $lang['RESET_PSW']; ?></legend>

			<input type='hidden' name='reset_form_visible' id='reset_form_visible' value='0'/>

			<div class='short_explanation'>* <?php echo $lang['REQ']; ?></div>

			<div class='container'>
				<label for='reset_email' ><?php echo $lang['EMAIL_ADDRESS']; ?>*:</label><br/>
				<input type='text' name='reset_email' id='reset_email' value='' maxlength="50" tabindex='520'/><br/>
				<span id='reset_email_errorloc' class='error'></span>
			</div>

			<div class='container'>
				<input type='button' id='resetSubmit' name='submit' value='<?php echo $lang['RESET_PSW']; ?>' tabindex='570'/>
			</div>
		</fieldset>
	</div>
</div>