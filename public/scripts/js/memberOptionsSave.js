// JavaScript Document
$(document).ready(function(){
	$(document).keyup(function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		if ( $('#oldPassword').is(":focus") == 1 ) {
			if ( code == 13 ) {
				event.preventDefault();
				submitDataChanges();
			}
		}
	});

	$("#saveMemberOptions").click(function(event){
		submitDataChanges();
	}); 
});

function submitDataChanges() {
	
	var name=$("#newName").val().trim();
	var phone=$("#newPhone").val().trim();
	var oldPassword=$("#oldPassword").val().trim();
	var newPassword=$("#newPassword").val().trim();
	var newPassword2=$("#newPassword2").val().trim();
	var notifications=$('input:radio[name=matchNotifications]:checked').val().trim(); //get checkbox value
	
	if ( oldPassword == '' ) {
		message_box.show_message('<u>Futsal-Time</u>',
								"Please enter your current password to change the settings!", false); //messagebox.js
		setTimeout(
			function() {
				message_box.close_message();
			},
			2000
		);
		return;
	}
	else { 
		if ( newPassword.length == 0 ) {
			newPassword=oldPassword;
		}
		else {
			if( newPassword.length < 8 || newPassword.length > 16 ) {
				message_box.show_message('<u>Futsal-Time</u>',
										"he new password needs to be between 8-16 characters long!", false); //messagebox.js
				setTimeout(
					function() {
						message_box.close_message();
					},
					2000
				);
				return;
			}
			else {
				if ( newPassword != newPassword2 ) {
					message_box.show_message('<u>Futsal-Time</u>',
											"The passwords entered do not match!!", false); //messagebox.js
					setTimeout(
						function() {
							message_box.close_message();
						},
						2000
					);
					return;
				}
			}
		}
	
		var request = $.ajax({
				type:		'POST',
				url:		'./scripts/php/memberOptions.php',
				data:		{
								name			: name,
								phone			: phone,
								old_password	: oldPassword,
								new_password	: newPassword,
								notifications	: notifications
							},
				dataType:	'json'			
			});
			
		request.done(function(data,textStatus,jqXHR){
			message_box.show_message(data.title, data.message, false); //messagebox.js
			setTimeout(
				function() {
					message_box.close_message();
					window.location.reload(true);//reload page
					window.location.href=window.location.href;//for safari
				},
				2000
			);
		});
 
		request.fail(function(jqXHR,textStatus,errorThrown){
			message_box.show_message('<u>Futsal-Time</u>',
								"The request was unsuccesful.<br />If the error persists please contact <i>support@futsal-time.com</i>"); //messagebox.js
		});
 
	}
}