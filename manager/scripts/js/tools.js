function updatePassword() {
	var newPsw = $('#newPassword').val().trim();
	var curPsw = $('#currentPassword').val().trim();
	
	if ( newPsw.length < 8 || curPsw.length < 8 ) {
		message_box.show_message('<u>Futsal-Time</u>',
								"The password needs to be between 8-16 characters long!", false); //messagebox.js
		setTimeout(
			function() {
				message_box.close_message();
			},
			2000
		);
		return;
	}
	
	if ( newPsw == curPsw ) {
		message_box.show_message('<u>Futsal-Time</u>',
								"Both passwords are the same!", false); //messagebox.js
		setTimeout(
			function() {
				message_box.close_message();
			},
			2000
		);
		return;
	}
	
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/updatePsw.php',
		data	: {
					n: newPsw,
					c: curPsw
				  },
		dataType:	'json'
	});

	request.done(function(data,textStatus,jqXHR){
		
		message_box.show_message(data.title, data.message, false); //messagebox.js
		setTimeout(
			function() {
				message_box.close_message();
			},
			2000
		);
		
	});
 
	request.fail(function(jqXHR,textStatus,errorThrown){
		//alert(jqXHR.responseText+' \n '+textStatus+' \n '+errorThrown);
		message_box.show_message('<u>Futsal-Time</u>',
								"The request was unsuccesful.<br />If the error persists please contact <i>support@futsal-time.com</i>"); //messagebox.js
	});	
}

function addMember() {


}

