function resendValidationEmail(email)
{
	var currentLang = getCookie('language');
	if ( currentLang == null )
		currentLang = 'en';
	var request = $.ajax({
			type:		'POST',
			url:		'./scripts/php/resendValidationEmail.php',
			data:		{
							email	: email, 
							lang	: currentLang 
						},
			dataType: "json"
		});
		
		request.done(function(data,textStatus,jqXHR){
			if ( data.state == 'success' ) {
				message_box.show_message(data.title, data.message); //messagebox.js
				hideResetForm(); //overlay.js
			}
			else if ( data.state == 'fail' ) {
				message_box.show_message(data.title, data.message); //messagebox.js
			}
			else {
				message_box.show_message('<u>Futsal-Time</u><br /><u><b>FATAL ERROR</b></u>',
											"Some error occured while resetting your password.<br /><br />If the error persists please contact <i>support@futsal-time.com</i><br />This really shouldn\'t... could\'t have happened...!" + 
											"<br /><br /><br />Type : <u>" + jqXHR.status + "</u>" +
											"<br />Desciption: <u> Virtual Error</u><br />"+
											"<br /><br />" + jqXHR.responseText + "<br />"); //messagebox.js
			}
		});
   
		request.fail(function(jqXHR,textStatus,errorThrown){
			message_box.show_message('<u>Futsal-Time</u>',
									"Some error occured while resetting your password.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
									"<br /><u>Type</u> : " + textStatus + 
									"<br /><u>Desciption</u> : " + errorThrown +
									"<br /><br />" + jqXHR.responseText); //messagebox.js
		});
		
		request.always(function(jqXHR,textStatus,errorThrown){
			hideRegisterForm();
		});
}