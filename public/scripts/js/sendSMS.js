function sendSMS(ajaxArgs, script) {
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/'+script+'.php',
		data	: { 
					args: ajaxArgs
				  },
		dataType: 'json'
	});
			
	request.done(function(data,textStatus,jqXHR) {
		if ( $('#message_box').is(':visible') ) {
			$('#message_box').append('<p>'+data.message+'</p>');
		}
		else {
			message_box.show_message(data.title, data.message); //messagebox.js
		}
		// setTimeout(
			// function() {
				// message_box.close_message();
			// },
			// 5000
		// );
		// alert(data.sms);
		return 1;
	});
	
	request.fail(function(jqXHR, textStatus, errorThrown) {
		message_box.show_message('<u>Futsal-Time</u>',
								"Some error occured while trying to send the sms.<br />" +
								"<br /><u>Type</u> : " + textStatus+
								"<br /><u>Desciption</u> : " + errorThrown +
								"<br /><br />" + jqXHR.responseText); //messagebox.js
		return -1;
	});	
}