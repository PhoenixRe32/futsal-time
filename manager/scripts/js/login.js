$(document).ready(function() {	
	$('#loginSubmit').click(function(event) { 
		event.preventDefault();
		postLoginData();
	});
	
	$('#login_name, #login_password').focusin(function(){ $('#login_form_focused').val(1); });
	
	$('#login_name, #login_password').focusout(function(){ $('#login_form_focused').val(0); });
	
	var arenaid = $.jStorage.get('arenaId');
	if ( arenaid ) $('#login_arena').val(arenaid); 
	
	var userid = $.jStorage.get('managerId');
	if ( userid ) $('#login_name').val(userid); 
	
	if ( arenaid ) {
		if ( userid )
			$('#login_password').focus();
		else
			$('#login_name').focus();
	}
	else {
		$('#login_arena').focus();
	}
	
	$(document).keyup(function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		if ( $('#login_form_focused').val() == 1 ) {
			if ( code == 13 ) {
				event.preventDefault();
				postLoginData();
			}
		}
	});
});

function postLoginData()
{
	var arena = $('#login_arena').val().trim();
	var name = $('#login_name').val().trim();
	var password = $('#login_password').val().trim();
	
	if ( password.length < 8 ) {
		message_box.show_message('<u>Futsal-Time</u>',
									"<br />Incorrect password"); //messagebox.js
		setTimeout(
			function() {
				message_box.close_message();
			},
			3500
		);
	}
	else if ( password.length > 16 ) {
		message_box.show_message('<u>Futsal-Time</u>',
									"<br />Incorrect password"); //messagebox.js
		setTimeout(
			function() {
				message_box.close_message();
			},
			3500
		);
	}
	else {
		var request = $.ajax({
			type:		'POST',
			url:		'./scripts/php/login.php',
			data:		{
							login_arena		: arena,
							login_name		: name, 
							login_password	: password 
						},
			dataType:	"json"
		});
		
		request.done(function(data,textStatus,jqXHR) {
			if ( data.state == 'success' ) {
				window.location.assign("./view.php?caller=calendar");
			}
			else if ( data.state == 'fail' ) {
				message_box.show_message(data.title, data.message); //messagebox.js
			}
			else {
				message_box.show_message('<u>Futsal-Time</u><br /><u><b>FATAL ERROR</b></u>',
										"<br />Some error occured while logging in.<br /><br />If the error persists please contact <i>support@futsal-time.com</i><br />This really shouldn\'t... could\'t have happened...!" + 
										"<br /><br /><br />Type : <u>" + jqXHR.status + "</u>" +
										"<br />Desciption: <u> Virtual Error</u><br />" +
										"<br /><br />" + jqXHR.responseText + "<br />"); //messagebox.js
			}
			
			$.jStorage.set('managerId', name);
			$.jStorage.set('arenaId', arena);
		});
			
		request.fail(function(jqXHR, textStatus, errorThrown) {
			message_box.show_message('<u>Futsal-Time</u>',
								"Some error occured while logging in.<br />If the error persists please contact <i>support@futsal-time.com</i><br />"+
								"<br /><u>Type</u> : " + textStatus +
								"<br /><u>Desciption</u> : " + errorThrown +
								"<br /><br />" + jqXHR.responseText); //messagebox.js
		});
		
	}
}
