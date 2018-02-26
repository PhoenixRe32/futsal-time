$(document).ready(function() {
	$('#clck1').click();
});

function updateReputation(customerPhone, arenaId, el) {
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/reputation.php',
		data	: {
					p : customerPhone,
					a : arenaId,
					v : el.value
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
				2500
			);
			
			if ( el.value == 'GOOD' ) color = 'Gold';
			else if ( el.value == 'BAD' ) color = 'FireBrick';
			else if ( el.value == 'NEUTRAL' ) color = 'Gainsboro';
			$('#'+customerPhone+'_row').css({'background-color':color});
		}
		else if ( data.state == 'fail' ) {
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

function updateRepNotes(customerPhone, arenaId) {
	var value = prompt("Enter any relevant notes",$('#'+customerPhone+'_notes').html());
	if ( value == null ) return;
	else value = value.trim();
	
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/reputationNotes.php',
		data	: {
					p : customerPhone,
					a : arenaId,
					v : value
				  },
		dataType:	'json'
	});

	request.done(function(data,textStatus,jqXHR){
		if ( data.state == 'success' ) {
			message_box.show_message(data.title, data.message, false); //messagebox.js
			setTimeout(
				function() {
					$('#'+customerPhone+'_notes').html(value);
					message_box.close_message();
				},
				2500
			);
			// $('#'+customerPhone).fadeOut('slow', function() {
				// $('#'+customerPhone).html(value).fadeIn('slow');
			// });
		}
		else if ( data.state == 'fail' ) {
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

function updateRepName(customerPhone) {
	if ( $('#'+customerPhone+'_name').html().trim() != '' ) return;
	var value = prompt("Enter name",$('#'+customerPhone+'_name').html());
	if ( value == null ) return;
	else if ( value.trim() != '' ) value = value.trim() ;
	else value = value.trim();
	
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/reputationName.php',
		data	: {
					p : customerPhone,
					v : value
				  },
		dataType:	'json'
	});

	request.done(function(data,textStatus,jqXHR){
		if ( data.state == 'success' ) {
			message_box.show_message(data.title, data.message, false); //messagebox.js
			setTimeout(
				function() {
					$('#'+customerPhone+'_name').html(value);
					message_box.close_message();
				},
				2500
			);
			// $('#'+customerPhone).fadeOut('slow', function() {
				// $('#'+customerPhone).html(value).fadeIn('slow');
			// });
		}
		else if ( data.state == 'fail' ) {
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

function getReputation(order, arenaId) {
	var dir = $('#direction').val();
	if ( dir == 'ASC' ) $('#direction').val('DESC');
	else if ( dir == 'DESC' ) $('#direction').val('ASC');
	else { $('#direction').val('ASC'); dir = 'ASC';}

	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/reputationGet.php',
		data	: {
					o : order,
					a : arenaId,
					d : dir
				  },
		dataType:	'json'
	});

	request.done(function(data,textStatus,jqXHR){
		if ( data.state == 'success' ) {
			$('#member_list tbody').html(data.message);
		}
		else if ( data.state == 'fail' ) {
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
		//alert(jqXHR.responseText+textStatus+errorThrown)
		message_box.show_message('<u>Futsal-Time</u>',
								"The request was unsuccesful.<br />If the error persists please contact <i>support@futsal-time.com</i>"); //messagebox.js
	});
}

function removeMember(customerPhone, arenaId) {
	var origBackCol = $('#'+customerPhone+'_row').css('background-color');
	$('#'+customerPhone+'_row').css({'background-color':'red'});
	var value = confirm("Are you certain you want to remove this member ("+$('#'+customerPhone+'_name').html()+") from the database?");
	if ( value == false ) {
		$('#'+customerPhone+'_row').css({'background-color':origBackCol});
		return;
	}
	
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/removeMember.php',
		data	: { p : customerPhone, i : arenaId },
		dataType:	'json'
	});

	request.done(function(data,textStatus,jqXHR){
		if ( data.state == 'success' ) {
			message_box.show_message(data.title, data.message, false); //messagebox.js
			setTimeout(
				function() {
					message_box.close_message();
					// window.location.reload(true);//reload page
					// window.location.href=window.location.href;//for safari
				},
				2500
			);
			$('#'+customerPhone+'_row').remove();
		}
		else if ( data.state == 'fail' ) {
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