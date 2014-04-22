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
		var match = /^(99|96|97)[ ]?-?[ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?$/.test(smsP);
		
		if ( !match ) {
			message_box.show_message('<u>Futsal-Time</u>',
									"The mobile phone number used as the SMS contact phone was invalid", false); //messagebox.js
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

function addSMSPeriod() {
	var startTime = $('#smsTimesF').val();
	var endTime = $('#smsTimesT').val();

	if ( startTime > endTime ) {
		message_box.show_message('<u>Futsal-Time</u>',
									"The starting time was after the ending time.", true); //messagebox.js
		setTimeout(
			function() {
				message_box.close_message();
			},
			2500
		);
		return ;
	}
	else if ( startTime == endTime ) {
		message_box.show_message('<u>Futsal-Time</u>',
									"The starting and ending time are the same.", true); //messagebox.js
		setTimeout(
			function() {
				message_box.close_message();
			},
			2500
		);
		return ;
	}
		
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/settings.php',
		data	: {
					mode: 'smsAdd',
					s	: startTime,
					e	: endTime
				  },
		dataType:	'json'
	});

	request.done(function(data,textStatus,jqXHR){
		if ( data.state == 'success' ) {
			message_box.show_message(data.title, data.message, false); //messagebox.js
			setTimeout(
				function() {
					message_box.close_message();
				},
				1000
			);
			
			var trHtml = "<tr id='smsPer"+data.id+"' class='smsOptions'>";
			trHtml += "<td width='200px' align='right' style='line-height:2.5'></td>";
			trHtml += "<td width='200px' style='padding-left:20px;'>"
			trHtml += "	<input type='text' readonly='readonly' style='width:100%; text-align:center;' value='"+startTime+" to "+endTime+"'/>"
			trHtml += "</td>"
			trHtml += "<td>"
			trHtml += "	<input type='button' value='Remove' onclick='removeSMSPeriod("+data.id+")' />"
			trHtml += "</td>"	
			trHtml += "</tr>";
			$('#manager_settings tr:last').after(trHtml);
		}
		else {
			message_box.show_message(data.title, data.message, false); //messagebox.js
			setTimeout(
				function() {
					message_box.close_message();
				},
				2000
			);
		}
	});
 
	request.fail(function(jqXHR,textStatus,errorThrown){
		message_box.show_message('<u>Futsal-Time</u>',
								"The request was unsuccesful.<br />If the error persists please contact <i>support@futsal-time.com</i>"); //messagebox.js
	});	
}

function removeSMSPeriod(id) {
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/settings.php',
		data	: {
					mode: 'smsDel',
					id	: id
				  },
		dataType:	'json'
	});

	request.done(function(data,textStatus,jqXHR){
		if ( data.state == 'success' ) {
			message_box.show_message(data.title, data.message, false); //messagebox.js
			setTimeout(
				function() {
					message_box.close_message();
				},
				1000
			);
			$('#smsPer'+id).remove();
		}
		else {
			message_box.show_message(data.title, data.message, false); //messagebox.js
			setTimeout(
				function() {
					message_box.close_message();
				},
				2000
			);
		}
	});
 
	request.fail(function(jqXHR,textStatus,errorThrown){
		message_box.show_message('<u>Futsal-Time</u>',
								"The request was unsuccesful.<br />If the error persists please contact <i>support@futsal-time.com</i>"); //messagebox.js
	});

}