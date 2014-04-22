// JavaScript Document
$(document).ready(function(){	
	$('#reset_close').click(function() {
		hideResetForm();
	});
	
	$("#reset_close").focusout(function(event) {
		event.preventDefault();
		$('#reset_email').focus();
	});
	
	$(document).keyup(function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		if ( $('#reset_form_visible').val() == 1 ) {
			if ( code == 27 ) {
				event.preventDefault();
				hideResetForm();
			}
		}
	});
	
	$("#resetSubmit").focusout(function(event) {
		event.preventDefault();
		$('#reset_email').focus();
	});
	
	$("#resetSubmit").click(function(event){
		event.preventDefault();
		var request = $.ajax({
			type:		'POST',
			url:		'./scripts/php/resetPassword.php',
			data:		{
							email	: $('#reset_email').val(), 
							lang	: getCookie('language') 
						},
			dataType: "json"
		});
		
		request.done(function(data,textStatus,jqXHR){
			if ( data.state == 'success' ) {
				message_box.show_message(data.title, data.message); //messagebox.js
				hideResetForm(); //overlay.js
			}
			else if ( data.state == 'fail' ) {
				message_box.show_message(data.title, data.message); //messagebox.js
				hideResetForm(); //overlay.js
			}
			else {
				message_box.show_message('<u>Futsal-Time</u><br /><u><b>FATAL ERROR</b></u>',
											"Some error occured while resetting your password.<br /><br />If the error persists please contact <i>support@futsal-time.com</i><br />This really shouldn\'t... could\'t have happened...!" + 
											"<br /><br /><br />Type : <u>" + jqXHR.status + "</u>" +
											"<br />Desciption: <u> Virtual Error</u><br />"+
											"<br /><br />" + jqXHR.responseText + "<br />"); //messagebox.js
			}
		});
   
		request.fail(function(jqXHR,textStatus,errorThrown){
			message_box.show_message('<u>Futsal-Time</u>',
									"Some error occured while resetting your password.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
									"<br /><u>Type</u> : " + textStatus +
									"<br /><u>Desciption</u> : " + errorThrown + 
									"<br /><br />" + jqXHR.responseText ); //messagebox.js
		});   
	}); 
});

function resetPsw()
{
	$('#overlay').fadeIn('fast',function() {
			$('#reset_box').css({'display':'block'}).animate({'left':'50%'},500);
			$('#reset_form_visible').val(1);
			$('#reset_email').val($('#login_email').val());
	});
	window.setTimeout(  
		function() {  
			$('#reset_email').focus();
		},  
		350
	);
}

function resetPsw2(email)
{
	message_box.close_message();
	hideRegisterForm();
	
	window.setTimeout(
		function() {  
			$('#overlay').fadeIn('fast',function() {
				$('#reset_box').css({'display':'block'}).animate({'left':'50%'},500);
				$('#reset_form_visible').val(1);
				$('#reset_email').val(email);
			});
			window.setTimeout(
				function() {  
					$('#reset_email').focus();
				},  
				350
			);
		},  
		700
	);
	
	
}


function hideResetForm()
{
	$('#reset_form_visible').val(0);
	$('#reset_box').animate({'left':'-50%'},500,function() {
		$('#overlay').fadeOut('fast');
		$(".error").html('');
		$('#reset_email').val('');
		$('#reset_box').css({'display':'none'});
	});
}