$(document).ready(function(){
	$('.settings').change(function(event) {
		if ( event.target.id == 'divC' ) {
			if ( $('#divM').val() > $('#divC').val() )
				$('#divM').prop("selectedIndex", $('#divC').prop("selectedIndex"));
		}
		else if ( event.target.id == 'divM' ) {
			if ( $('#divC').val() < $('#divM').val() )
				$('#divC').prop("selectedIndex", $('#divM').prop("selectedIndex"));
		}
		updateSettings();
	});
});

function updateSettings() {
	var bl = $('#bl').val();
	var sfC = $('#divC').val();
	var sfM = $('#divM').val();
	var rf = $('#rf').val();
	var cp = $('#contact').val().trim();
	var smsC = $('#smsC').val();
	var smsM = $('#smsM').val();
	var smsP = $('#smsContact').val().trim();
	
	sfC = ( sfM > sfC ) ? sfM : sfC;
	
	
	if ( smsP != '' ) {
		smsP = smsP.replace(/ |-/g,"");
		var match = /^(99|96|97)[0-9]{6}$/.test(smsP);
		
		if ( !match ) {
			message_box.show_message('<u>Futsal-Time</u>',
									"The mobile phone number used as the SMS contact phone was invalid ("+smsP+")", false); //messagebox.js
			setTimeout(
				function() {
					message_box.close_message();
				},
				2500
			);
			return ;
		}
	}
	
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/settings.php',
		data	: {
					mode: 'set',
					bl	: bl,
					sfC	: sfC,
					sfM	: sfM,
					rf	: rf,
					cp	: cp,
					smsC: smsC,
					smsM: smsM,
					smsP: smsP
				  },
		dataType:	'json'
	});

	request.done(function(data,textStatus,jqXHR){
		if ( data.state == 'success' ) {
			if ( smsM == 0 ) {
				$('.smsOptions').slideUp('slow', function(){
					message_box.show_message(data.title, data.message, false); //messagebox.js
						setTimeout(
							function() {
								message_box.close_message();
							},
							1000
						);				
				});
			}
			else {
				$('.smsOptions').slideDown('slow', function(){
					message_box.show_message(data.title, data.message, false); //messagebox.js
						setTimeout(
							function() {
								message_box.close_message();
							},
							1000
						);
				});
			}
			
		}
		else {
			message_box.show_message(data.title, data.message, false); //messagebox.js
			setTimeout(
				function() {
					message_box.close_message();
				},
				2500
			);
		}
	});
 
	request.fail(function(jqXHR,textStatus,errorThrown){
		message_box.show_message('<u>Futsal-Time</u>',
								"The request was unsuccesful.<br />If the error persists please contact <i>support@futsal-time.com</i>"); //messagebox.js
	});	
}