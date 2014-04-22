$(document).ready(function() {
	var timer;
	
	$(window).on('onbeforeunload beforeunload unload', function() {
		// figure out whether you need to prompt or not
		if ( $('#reservation_form_visible').val() == 1 ) {
			hideReservationForm();
		}
	});
	
	$('#reservation_close').click(function() {
		hideReservationForm();
	});
	
	$(document).keyup(function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		if ( $('#reservation_form_visible').val() == 1 ) {
			if ( code == 27 ) {
				event.preventDefault();
				hideReservationForm();
			}
		}
	});
	
	$(document).keyup(function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		if ( $('#reservation_form_visible').val() == 1 ) {
			if ( code == 13 ) {
				event.preventDefault();
			}
		}
	});
	
	$("#reservationSubmit").focusout(function(event) {
		event.preventDefault();
		$('#customerName').focus();
	});
	
	$("#reservation_close").focusout(function(event) {
		event.preventDefault();
		$('#customerName').focus();
	});
});

function reservation(size, arenaName, arenaID, dateTime, requestType, challenges, contacts, element, smsSendingMan, smsSendingCus) 
{
	alert("Online bookings disabled for now.\nFor bookings please call at one of the folowing numbers:\n"+contacts);return;
	var click = $(element).attr('onclick');
	$(element).attr('onclick', 'return false;');
	
	timer = setTimeout(  
		function() {  
			hideReservationForm();
		},  
		60000
	);
	
	var currentLang = getCookie('language');
	
	var date = new Date();
	var date_str=date.getFullYear()+'-'+('0'+(date.getMonth()+1)).substr(-2,2)+'-'+('0'+date.getDate()).substr(-2,2)+' '+('0'+date.getHours()).substr(-2,2)+':'+('0'+date.getMinutes()).substr(-2,2)+':00';	
	if ( date_str >= dateTime) {
		clearTimeout(timer);
		if ( currentLang == 'en' ) 
			message_box.show_message('<u>Futsal-Time</u>',
									"You can't book a game in the past!", true); //messagebox.js
		else if ( currentLang == 'gr' ) 
			message_box.show_message('<u>Futsal-Time</u>',
									"Δεν μπορείς να κλείσεις παιχνίδια στο παρελθόν!", true); //messagebox.js
		else
			message_box.show_message('<u>Futsal-Time</u>',
									"Δεν μπορείς να κλείσεις παιχνίδια στο παρελθόν!", true); //messagebox.js
		setTimeout(
			function() {
				message_box.close_message();
			},
			2500
		);
		$(element).attr('onclick', click);
		return ;
	}
	
	if (
			$('#customerName').val().trim() == '' && 
			$('#customerEmail').val().trim() == '' &&
			$('#customerPhone').val().trim() == ''
		){
		clearTimeout(timer);
		if ( currentLang == 'en' ) 
			message_box.show_message('<u>Futsal-Time</u>',
									"You must be logged in to be able to make online reservations. If you are not registered, then make an account and try again.<div id='login_for_lazy_ilethius' style='margin-left:45px;'></div>"); //messagebox.js
		else if ( currentLang == 'gr' ) 
			message_box.show_message('<u>Futsal-Time</u>',
									"Πρέπει να είσαι logged in για να κάνεις κρατήσεις online. Αν δεν είστε εγγεγραμέος τότε δημιουργήστε ένα λογαριασμό και δοκιμάστε πάλι.<div id='login_for_lazy_ilethius' style='margin-left:45px;'></div>"); //messagebox.js
		else
			message_box.show_message('<u>Futsal-Time</u>',
									"Πρέπει να είσαι logged in για να κάνεις κρατήσεις online. Αν δεν είστε εγγεγραμέος τότε δημιουργήστε ένα λογαριασμό και δοκιμάστε πάλι.<div id='login_for_lazy_ilethius' style='margin-left:45px;'></div>"); //messagebox.js
									
		$(element).attr('onclick', click);
		
		$('#login_for_lazy_ilethius').append($('#login_box_frame'));
		$('#login_email').focus();
	} 
	else {
		var request_in = $.ajax({
			type	:	'POST',
			url		:	'./scripts/php/bookPermissions.php',
			data	:	{  
							arenaID	: arenaID
						},
			dataType:	"json"
		});
		
		request_in.done(function(data,textStatus,jqXHR) {
			if ( data.state == 'fail' ) {		
				message_box.show_message(data.title, data.message); //messagebox.js
			}
			else {
				var request = $.ajax({
					type	: 'POST',
					url		: './scripts/php/reserve.php',
					data	: { 
								reqType		: requestType, 
								arenaID		: arenaID, 
								date		: dateTime.substring(0,10).trim(), 
								time		: dateTime.substring(11,19).trim(), 
								size		: size.trim(),
								email		: $('#customerEmail').val(),
								phone		: $('#customerPhone').val(), 
								lang		: currentLang
							},
					dataType:	"json"
				});
				
				request.done(function(data,textStatus,jqXHR) {
					if ( data.state == 'success' ) {
						$("#reservationSubmit").off('click');
						$("#reservationSubmit").on('click', function(event) {
							event.preventDefault();
							finalizeReservation(smsSendingMan, smsSendingCus);
						});						
						
						var dateModF = dateTime.substring(0,10).substr(8,2) + '-' + dateTime.substring(0,10).substr(5,2) + '-' + dateTime.substring(0,10).substr(0,4);
						var timeModF = dateTime.substring(11,16);
						
						$('#dateTime').html(dateModF + ' @ ' + timeModF);
						$('#arena').html(arenaName);
						$('#arenaID').val(arenaID);
						$('#arenaSize').val(size);
						$('#challenges').val(challenges);
						$('#gameDate').val(dateModF);
						$('#gameTime').val(timeModF);
						$('#gameField').val(data.message);
						$('#arenaPhone').val(contacts);
						$('#acceptChal').val(0);
						$("#game_type1_cont").show();
						$("#game_type2_cont").show();
						if ( size.trim() == '9X9' ) {
							$("#game_type_options input[id=game_type1]:radio").attr('disabled',false);
							$("#game_type_options input[id=game_type2]:radio").attr('disabled',true);
							$("#game_type_options input[id=game_type1]:radio").attr('checked',true);
							$("#game_type2_cont").hide();
						}
// IF MUST CAN USE VAR challenges TO FORCE CHALLENGE BUTTON IF EXISTING CHALLENGE BY DISABLING IT IN MATCH BUTTON WHEN PRESSED						
						else {
							if ( requestType == 'PENDING' && challenges > 0 ) {
								$("#game_type_options input[id=game_type1]:radio").attr('disabled',false);
								$("#game_type_options input[id=game_type2]:radio").attr('disabled',true);
								$("#game_type_options input[id=game_type1]:radio").attr('checked',true);
								$("#game_type2_cont").hide();
							}
							else if ( requestType == 'PENDING' && challenges == 0 ) {
								$("#game_type_options input[id=game_type1]:radio").attr('disabled',false);
								$("#game_type_options input[id=game_type2]:radio").attr('disabled',false);
								$("#game_type_options input[id=game_type1]:radio").attr('checked',true);
							}
							else if ( requestType == 'ACCEPT' ) {
								$("#game_type_options input[id=game_type1]:radio").attr('disabled',true);
								$("#game_type_options input[id=game_type2]:radio").attr('disabled',false);
								$("#game_type_options input[id=game_type2]:radio").attr('checked',true);
								$('#acceptChal').val(1);
								$("#game_type1_cont").hide();
							}
						}
						
						//$('#overlay').css({'z-index':'500'});
						$('#overlay').fadeIn('fast',function() {
							$('#include_reservation_part').css({'display':'block'}).animate({'left':'50%'},500);
							$('#reservation_form_visible').val(1);
							$('#game_type2').focus();
							$('#game_type1').focus();
						});
					}
					else if ( data.state == 'fail' || data.state == 'fail_success') {
						message_box.show_message(data.title, data.message); //messagebox.js
					}
				});
				
				request.fail(function(jqXHR, textStatus, errorThrown) {
					message_box.show_message('<u>Futsal-Time</u>',
											"Some error occured while reserving the slot.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
											"<br /><u>Type</u> : " + textStatus +
											"<br /><u>Desciption</u> : " + errorThrown +
											"<br /><br />" + jqXHR.responseText); //messagebox.js
				});
			}
		});
		
		request_in.fail(function(jqXHR, textStatus, errorThrown) {
			message_box.show_message('<u>Futsal-Time</u>',
									"Some error occured.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
									"<br /><u>Type</u> : " + textStatus +
									"<br /><u>Desciption</u> : " + errorThrown +
									"<br /><br />" + jqXHR.responseText); //messagebox.js
		});
		
		request_in.always(function(jqXHR, textStatus) {
			$(element).attr('onclick', click);
		});			
	}
}

function finalizeReservation(smsSendingMan, smsSendingCus)
{
	alert("Disabled for now");return;
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/reserve.php',
		data	: { 
					reqType	: 'FINALIZING', 
					arenaID	: $('#arenaID').val().trim(),
					size	: $('#arenaSize').val().trim(),
					date	: $('#gameDate').val().substr(6,4) + '-' + $('#gameDate').val().substr(3,2) + '-' + $('#gameDate').val().substr(0,2),
					time	: $('#gameTime').val().trim(), 
					duration: $('input[name=gameDuration]:checked').val(), // For Future Use
					type	: $('input[name=gameType]:checked').val(), 
					cost	: '1', // For Future Use
					opponent: '', // For Future Use
					challeng: $('#challenges').val(),
					accept	: $('#acceptChal').val(),
					phone 	: $('#customerPhone').val().trim(),
					email	: $('#customerEmail').val(), 
					lang	: getCookie('language')
				},
		dataType:	"json"
	});
	
	request.done(function(data,textStatus,jqXHR) {
		hideReservationForm();
		if ( data.state == 'success' ) {
				message_box.show_message(data.title, data.message); //messagebox.js
				
				if ( smsSendingMan || smsSendingCus ) {
					var ajaxArgs = 'arenaID='+data.arenaID+'&customerP='+data.customerPhone+'&opponentP='+data.opponentPhone+'&customerN='+data.customerName+'&opponentN='+data.opponentName+'&date='+data.date+'&time='+data.time+'&gameType='+data.gameType+'&fieldSize='+data.gameSize+'&fieldId='+data.fieldId+'&acceptChal='+data.accept+'&mode=BOOKING'; 
					// alert(ajaxArgs);
					sendSMS(ajaxArgs, 'sendResSMS');
				}
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
		//$('#searchButton').trigger('click');
	});
	
	request.fail(function(jqXHR, textStatus, errorThrown) {
		hideReservationForm();
		message_box.show_message('<u>Futsal-Time</u>',
								"Some error occured while finalizing your reservation.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
								"<br /><u>Type</u> : " + textStatus +
								"<br /><u>Desciption</u> : " + errorThrown +
								"<br /><br />" + jqXHR.responseText); //messagebox.js
	});
}

function hideReservationForm()
{
	$("#reservationSubmit").off('click');
	postSearchParameters();
	clearTimeout(timer);
	
	$('#include_reservation_part').animate({'top':'4000px'},800,function() {
		$('#overlay').fadeOut('fast');
		//$('#overlay').css({'z-index':'100'});
		$('#include_reservation_part').css({'left':'150%', 'top':'15%', 'display':'none'});
	});
	$('#reservation_form_visible').val(0);
	$('#acceptChal').val(0);
	
	$('#gameField').val('');
	$('#dateTime').html('');
	$('#arena').html('');
	$('#arenaID').val('');
	$('#gameDate').val('');
	$('#gameTime').val('');
	$('#arenaPhone').val('');	
}

function addAlert(size, arenaId, dateTime, requestType) {
	var add = false;
	var currentLang = getCookie('language');
	if ( currentLang == 'en' )
		add = confirm("Would you like to be notified if a cancellation takes place?");
	else if ( currentLang == 'gr' )
		add = confirm("Θα θέλατε να ενημερωθείτε αν υπάρξει κάποια ακύρωση?");
	else
		add = confirm("Would you like to be notified if a cancellation takes place?");
	
	if ( !add ) return;
	
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/addAlert.php',
		data	: {
					reqType		: requestType, 
					arenaId		: arenaId, 
					date		: dateTime.substr(0,10).trim(), 
					time		: dateTime.substr(11).trim(), 
					size		: size.trim(),
					lang		: currentLang
				  },
		dataType: 'json'
	});
	
	request.done(function(data, textStatus, jqXHR) {
		if ( data.state == 'fail_success' ) {
			alert(data.message);
		}
		else if ( data.state == 'success' ) {
			alert(data.message);
		}
		else if ( data.state == 'fail' ) {
			alert(data.message);
		}
		else
			alert('else');
	});
	
	request.fail(function(jqXHR,textStatus,errorThrown){
		message_box.show_message('<u>Futsal-Time</u>',
								"The request was unsuccesful.<br />If the error persists please contact <i>support@futsal-time.com</i>"); //messagebox.js
	});	
}
