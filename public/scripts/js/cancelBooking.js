// JavaScript Document
function cancelBooking(reservationID) {
	alert("Disabled for now");return;
	var request = $.ajax({
		type	:	'POST',
		url		:	'./scripts/php/cancelBooking.php',
		data	:	{
						reservationID : reservationID
					},
		dataType:	"json"
	});
   
	request.done(function(data,textStatus,jqXHR){
		message_box.show_message(data.title, data.message, false); //messagebox.js
		setTimeout(
			function() {
				window.location.reload(true);//reload page
				window.location.href=window.location.href;//for safari
			},
			2000
		);
   });
   
   request.fail(function(jqXHR, textStatus, errorThrown) {
			message_box.show_message('<u>Futsal-Time</u>',
									"Some error occured while cancelling the reservation.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
									"<br /><u>Type</u> : " + textStatus +
									"<br /><u>Desciption</u> :" + errorThrown +
									"<br /><br />" + jqXHR.responseText); //messagebox.js
		});   
 }