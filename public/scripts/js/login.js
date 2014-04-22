$(document).ready(function() {	
	$('#include_useful_part').append($('#login_box_frame'));

	$('#loginSubmit').click(function(event) { 
		event.preventDefault();
		postLoginData();
	});
	
	$('#login_email, #login_password').focusin(function(){ $('#login_form_focused').val(1); });
	
	$('#login_email, #login_password').focusout(function(){ $('#login_form_focused').val(0); });
	
	$(document).keyup(function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		if ( $('#login_form_focused').val() == 1 ) {
			if ( code == 13 ) {
				event.preventDefault();
				postLoginData();
			}
		}
	});
	
	$('#logout').bind('contextmenu click', function(event){
		event.preventDefault();
		window.location.assign("index.php?caller=logout");
		return false;
	});
	
	var userid = $.jStorage.get('clientId');
	if ( userid ) $('#login_email').val(userid);	
});

function postLoginData() {
	var currentLang = getCookie('language');
	
	$('#login_message').html('');
	$('#login_message').removeClass();
	$('#login_message').slideUp('fast');
	
	var email = $('#login_email').val().trim();
	var password = $('#login_password').val().trim();
	
	var emailMatch = /^([\w\.\-]+)@([\w\-]+)((\.(\w){2,8})+)$/.test(email);
	if ( !emailMatch ) {
		$('#login_message').addClass('error').html('Invalid email.').slideDown('fast');
	}
	else {
			var request = $.ajax({
				type	: 'POST',
				url		: './scripts/php/login.php',
				data	: {
							login_email		: email, 
							login_password	: password, 
							lang			: getCookie('language') 
						},
				dataType:	"json"
			});
			
			request.done(function(data,textStatus,jqXHR) {
				if ( data.state == 'success' ) {
					$('#login_message').addClass('success').html("<span style='display:block'>"+data.name+"</span><span style='display:block'>"+data.email+"</span><span style='display:block'>"+""+"</span><hr style='width:50%'/><span style='display:block'><a href='index.php?caller=logout' id='logout'><i><b><u>Logout</u></b></i></a></span>");
					$('#logout').bind('contextmenu click', function(event){
						event.preventDefault();
						window.location.assign("index.php?caller=logout");
						return false;
					});
					
					$('#login_form').slideUp('slow');
					$('#login_message').slideDown('slow', function(){message_box.close_message();});
					$('#userLink').fadeIn('slow');
					$('#customerName').val(data.name).attr('readonly','readonly');
					$('#customerEmail').val(data.email).attr('readonly','readonly');
					$('#customerPhone').val(data.phone).attr('readonly','readonly');
					
					$.jStorage.set('clientId', email);
				}
				else if ( data.state == 'fail_psw' ) {
					if ( currentLang == 'en' )
						var message = data.message+"<br><b><font color='red'><span id='resetPsw' style='cursor:pointer' onClick='resetPsw()'>Reset Password?</span></font></b>";
					else if ( currentLang == 'gr' )
						var message = data.message+"<br><b><font color='red'><span id='resetPsw' style='cursor:pointer' onClick='resetPsw()'>Ξαναρύθμισε Κωδικό;</span></font></b>";
					else
						var message = data.message+"<br><b><font color='red'><span id='resetPsw' style='cursor:pointer' onClick='resetPsw()'>Reset Password?</span></font></b>";
					$('#login_message').addClass('error').html(message).slideDown('fast');
				}
				else if ( data.state == 'fail' ) {
					var message = data.message;
					$('#login_message').addClass('error').html(message).slideDown('fast');
				}
				else {
					message_box.show_message('<u>Futsal-Time</u><br /><u><b>FATAL ERROR</b></u>',
											"Some error occured while logging in.<br />If the error persists please contact <i>support@futsal-time.com</i><br />This really shouldn\'t... could\'t have happened...!<br />" + 
											"<br /><u>Type</u> : " + jqXHR.status +
											"<br /><u>Desciption</u> : <u> Virtual Error</u>"+
											"<br /><br />" + jqXHR.responseText); //messagebox.js
				}
			});
			
			request.fail(function(jqXHR, textStatus, errorThrown) {
				message_box.show_message('<u>Futsal-Time</u>',
									"Some error occured while logging in.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
									"<br /><u>Type</u> : " + textStatus +
									"<br /><u>Desciption</u> : " + errorThrown + 
									"<br /><br />" + jqXHR.responseText); //messagebox.js
			});
	}
}