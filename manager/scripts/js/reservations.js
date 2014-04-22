function reserve(date, time, accept, fieldId, smsSendingMan, smsSendingCus) {
	//check phone email
	$('.error').html('');
	
	var customerName = $('#customerName').val().trim();
	var customerEmail = $('#customerEmail').val().trim();
	var customerPhone = $('#customerPhone').val().trim();
	var opponentName = $('#opponentName').val().trim();
	var opponentEmail = $('#opponentEmail').val().trim();
	var opponentPhone = $('#opponentPhone').val().trim();
	var gameType = $('input[name=gameType]:checked').val();
	var gameSize = $('input[name=gameSize]:checked').val();
	
	if ( accept == 0 ) {
		if ( customerEmail.length > 0 ) {
			var emailMatch = /^([\w\.\-]+)@([\w\-]+)((\.(\w){2,8})+)$/.test(customerEmail);
			if ( !emailMatch ) {
				$('#customer_email_errorloc').html("Email address provided invalid.");
				return ;
			}
		}
		
		var match = /^(99|96|97|22|23|24|25|26)[ ]?-?[ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?$/.test(customerPhone);
		if ( !match ) {
			$('#customer_phone_errorloc').html("Phone provided invalid.");
			return ;
		}
	}
	else if ( accept == 1 ) {
		if ( opponentEmail.length > 0 ) {
			var emailMatch = /^([\w\.\-]+)@([\w\-]+)((\.(\w){2,8})+)$/.test(opponentEmail);
			if ( !emailMatch ) {
				$('#opponent_email_errorloc').html("Email address provided invalid.");
				return ;
			}
		}
		
		var match = /^(99|96|97|22|23|24|25|26)[ ]?-?[ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?$/.test(opponentPhone);
		if ( !match ) {
			$('#opponent_phone_errorloc').html("Phone provided invalid.");
			return ;
		}
	}
	
	if ( typeof(gameType) == 'undefined' || gameType == null)
	{
		$('#gameType_errorloc').html("Make a choice.");
		return ;
	}
	if ( typeof(gameSize) == 'undefined' || gameSize == null)
	{
		$('#gameSize_errorloc').html("Make a choice.");
		return ;
	}
	
	if (	( ( gameSize == '7X7' || gameSize == '9X9' || gameSize == '10X10' ) && gameType == 'CHALLENGE' )	||
			( ( gameSize == '7X7' || gameSize == '9X9' || gameSize == '10X10' ) && accept == 1 )				||
			( gameType == 'MATCH' && accept == 1 ) 
		) {
		$('#gameType_errorloc').html("Ivalid choice.");
		$('#gameSize_errorloc').html("Ivalid choice.");
		return ;
	}
	
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/reserve.php',
		data	: { 
					date	: date.substr(6,4) + '-' + date.substr(3,2) + '-' + date.substr(0,2),
					time	: time, 
					duration: 1, // For Future Use
					type	: gameType, 
					size	: gameSize,
					cost	: '1', // For Future Use
					accept	: accept,
					phone1 	: customerPhone,
					email1	: customerEmail,
					phone2 	: opponentPhone,
					email2	: opponentEmail,
					name1	: customerName,
					name2	: opponentName,
					fieldId	: fieldId
				},
		dataType:	"json"
	});
	
	request.done(function(data,textStatus,jqXHR) {
		//alert(data);return;
		clear();
		if ( 'undefined' === typeof(timeout) ) ; else clearTimeout(timeout);		
		if ( data.state == 'success' ) {
				message_box.show_message(data.title, data.message); //messagebox.js
				
				if ( smsSendingMan || smsSendingCus ) {
					var ajaxArgs = 'customerP='+customerPhone+'&opponentP='+opponentPhone+'&customerN='+customerName+'&opponentN='+opponentName+'&date='+date+'&time='+time+'&gameType='+gameType+'&fieldSize='+gameSize+'&fieldId='+fieldId+'&acceptChal='+accept+'&mode=BOOKING';
					sendSMS(ajaxArgs, 'sendResSMS');
				}
				
				getSchedule($('#dtTitle').html());
		}
		else if ( data.state == 'fail' ) {
			message_box.show_message(data.title, data.message); //messagebox.js
		}
		else {
			message_box.show_message('<u>Futsal-Time</u><br /><u><b>FATAL ERROR</b></u>',
										"<br />Some error occured while finalizing the reservation.<br /><br />If the error persists please contact <i>support@futsal-time.com</i><br />This really shouldn\'t... could\'t have happened...!" + 
										"<br /><br /><br />Type : <u>" + jqXHR.status + "</u>" +
										"<br />Desciption: <u> Virtual Error</u><br />"+
										"<br /><br />" + jqXHR.responseText + "<br />"); //messagebox.js
		}
		// setTimeout(
			// function() {
				//$("#datepicker").datepicker("setDate", new Date());
				// var dateObj = $('#datepicker').datepicker('getDate');
				// var dateText = $.datepicker.formatDate('dd-mm-yy DD', dateObj);
				// getSchedule($('#dtTitle').html());
			// },
			// 100
		// );
	});
	
	request.fail(function(jqXHR, textStatus, errorThrown) {
		clear();
		message_box.show_message('<u>Futsal-Time</u>',
								"Some error occured while finalizing your reservation.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
									"<br /><u>Type</u> : " + textStatus +
									"<br /><u>Desciption</u> : " + errorThrown + 
									"<br /><br />" + jqXHR.responseText); //messagebox.js
	});
}

function cancel(reservationID, smsSendingMan, smsSendingCus) {
	var request = $.ajax({
		type	:	'POST',
		url		:	'./scripts/php/cancelBooking.php',
		data	:	{
						reservationID : reservationID
					},
		dataType:	"json"
	});
   
	request.done(function(data,textStatus,jqXHR){
		clear();
		if ( 'undefined' === typeof(timeout) ) ; else clearTimeout(timeout);		
		if ( data.state == 'success' ) {
				message_box.show_message(data.title, data.message); //messagebox.js
				
				if ( smsSendingMan || smsSendingCus ) {
					var ajaxArgs = 'customerP='+data.customerPhone+'&opponentP='+data.opponentPhone+'&customerN='+data.customerName+'&opponentN='+data.opponentName+'&date='+data.date+'&time='+data.time+'&gameType='+data.gameType+'&fieldSize='+data.gameSize+'&fieldId='+data.fieldId+'&acceptChal='+data.accept+'&mode=CANCELATION'; 
					sendSMS(ajaxArgs, 'sendResSMS');
				}
				
				getSchedule($('#dtTitle').html());
		}
		else if ( data.state == 'fail' ) {
			message_box.show_message(data.title, data.message); //messagebox.js
		}
		else {
			message_box.show_message('<u>Futsal-Time</u><br /><u><b>FATAL ERROR</b></u>',
										"<br />Some error occured while finalizing the reservation.<br /><br />If the error persists please contact <i>support@futsal-time.com</i><br />This really shouldn\'t... could\'t have happened...!" + 
										"<br /><br /><br />Type : <u>" + jqXHR.status + "</u>" +
										"<br />Desciption: <u> Virtual Error</u><br />"+
										"<br /><br />" + jqXHR.responseText + "<br />"); //messagebox.js
		}
		
		getSchedule($('#dtTitle').html());
		// setTimeout(
			// function() {
				//$("#datepicker").datepicker("setDate", new Date());
				// var dateObj = $('#datepicker').datepicker('getDate');
				// var dateText = $.datepicker.formatDate('dd-mm-yy DD', dateObj);
				// getSchedule($('#dtTitle').html());
			// },
			// 100
		// );
   });
   
   request.fail(function(jqXHR, textStatus, errorThrown) {
			message_box.show_message('<u>Futsal-Time</u>',
									"Some error occured while cancelling the reservation.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
									"<br /><u>Type</u> : " + textStatus + "</u>" +
									"<br /><u>Desciption</u> : <u>" + errorThrown + "</u>" +
									"<br /><br />" + jqXHR.responseText + "<br />"); //messagebox.js
		});

}