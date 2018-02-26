/* 
 * JQuery borrowed by the "CSS and jQuery Tutorial: Overlay with Slide Out Box" tutorial
 * by Mary Lou (http://tympanus.net/codrops/author/crnacura/)
 * at codrops (http://tympanus.net/codrops/2009/12/03/css-and-jquery-tutorial-overlay-with-slide-out-box/).
 * Credit where credit is due. Our thanks.
 */
 
$(document).ready(function() {	
	$('#activator').click(function() {
		message_box.close_message();
		$('#overlay').fadeIn('fast',function() {
			var pwdwidget = new PasswordWidget('thepwddiv','password');
			pwdwidget.MakePWDWidget();
			
			var randomnumber = Math.floor(Math.random()*6+7);
			$('#var1').html(randomnumber);
			randomnumber = Math.floor(Math.random()*6);
			$('#var2').html(randomnumber);
			var signs = ["+","-","*"]
			randomnumber = Math.floor(Math.random()*3);
			$('#oper').html(signs[randomnumber]);
			if ( signs[randomnumber] == '+' ) {
				var val1 = parseInt($('#var1').html());
				var val2 = parseInt($('#var2').html());
				var res = parseInt(val1 + val2);
				$('#equVal').val(res);
			}
			else if ( signs[randomnumber] == '-' ) {
				var val1 = parseInt($('#var1').html());
				var val2 = parseInt($('#var2').html());
				var res = parseInt(val1 - val2);
				$('#equVal').val(res);
			}
			else if ( signs[randomnumber] == '*' ) {
				var val1 = parseInt($('#var1').html());
				var val2 = parseInt($('#var2').html());
				var res = parseInt(val1 * val2);
				$('#equVal').val(res);
			}
			
			var validEmail = /^([\w\.\-]+)@([\w\-]+)((\.(\w){2,8})+)$/.test($('#login_email').val());
			if ( validEmail ) {
				$('#email').val($('#login_email').val().trim());
				$('#emailAgain').val($('#login_email').val().trim());
			}
			
			$('#register_box').animate({'top':'20px'},500);
			$('#register_form_visible').val(1);
		});
		window.setTimeout(  
			function() {  
				$('#name').focus();
			},  
			700  
		);
	});
	
	$('#register_close').click(function() {
		hideRegisterForm();
	});
	
	$("#registerSubmit").click(function(event) {
		event.preventDefault();
		postRegisterData();
	});
	
	$("#registerSubmit").focusout(function(event) {
		event.preventDefault();
		$('#name').focus();
	});
	
	$("#register_close").focusout(function(event) {
		event.preventDefault();
		$('#name').focus();
	});
	
	$(document).keyup(function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		if ( $('#register_form_visible').val() == 1 ) {
			if ( code == 27 ) {
				event.preventDefault();
				hideRegisterForm();
			}
		}
	});
	
	$(document).keyup(function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		if ( $('#register_form_visible').val() == 1 ) {
			if ( code == 13 ) {
				event.preventDefault();
				postRegisterData();
			}
		}
	});
});

function checkRequirement(object)
{
	var errorId = '';
	
	if ( object.value.trim().length == 0 ) {
		errorId = "register_"+object.name+"_errorloc";
		$("#"+errorId).html("Please fill in the blank.");
		
		return false;
	}
	else {
		if ( object.name == 'email' ) {
			if ( !emailIsValid(object.value.trim()) ) return false;
			else return true;
		}
		else if ( object.name == 'emailAgain' ) {
			if ( !emailMatch(object.value.trim()) ) return false;
			else return true;
		}
		else if ( object.name == 'phone' ) {
			if ( !phoneIsValid(object.value.trim()) ) return false;
			else return true;
		}
		else if ( object.name == 'phoneAgain' ) {
			if ( !phoneMatch(object.value.trim()) ) return false;
			else return true;
		}
		else if ( object.name == 'password' ) {
			if ( !passwordLengthIsValid(object.value.length) ) return false;
			else return true;
		}
		else {
			return true;
		}
	}
}

function emailIsValid(email)
{
	var match = /^([\w\.\-]+)@([\w\-]+)((\.(\w){2,8})+)$/.test(email);
	
	if ( !match ) $("#register_email_errorloc").html("Please provide a valid email address.");

	return match;
}

function emailMatch(email)
{
	if ( ( email != $('#email').val().trim() ) ) {
		$("#register_emailAgain_errorloc").html("Email addresses provided don't match.");
		return false;
	}
	else {
		return true;
	}
}

function phoneIsValid(phone)
{
	var match = /^(99|96|97|22|23|24|25|26)[ ]?-?[ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?[0-9][ ]?$/.test(phone);
	
	if ( !match ) $("#register_phone_errorloc").html("Please provide a valid phone number.");

	return match;
}

function phoneMatch(phone)
{
	if ( ( phone != $('#phone').val().trim() ) ) {
		$("#register_phoneAgain_errorloc").html("Contact phones provided don't match.");
		return false;
	}
	else {
		return true;
	}
}

function passwordLengthIsValid(passwordLength)
{
	if ( passwordLength < 8 ) {
		$("#register_password_errorloc").html("Use at least 8 characters.");
		return false;
	}
	if ( passwordLength > 16 ) {
		$("#register_password_errorloc").html("Use no more than 16 characters.");
		return false;
	}
	else {
		return true;
	}
}

function postRegisterData()
{
	$('.error').html('');
	if	(	
			checkRequirement($("#name").get(0)) &
			checkRequirement($("#email").get(0)) &
			checkRequirement($("#emailAgain").get(0)) &
			checkRequirement($("#phone").get(0)) &
			checkRequirement($("#phoneAgain").get(0)) &
			checkRequirement($("#password_id").get(0)) &
			checkRequirement($("#humanVerification").get(0))
		) {
		
		var name = $('#name').val().trim();
		var email = $('#email').val().trim();
		var phone = $('#phone').val().trim();
		var password = $('#password_id').val().trim();
		var securityQuestion = $('#securityQuestion').val().trim();
		var humanVerification = $('#humanVerification').val().trim();
		var equVal = $('#equVal').val().trim();
		
		var currentLang = getCookie('language');
		if ( currentLang == null )
			currentLang = 'en';
		var request = $.ajax({
			type	: 'POST',
			url		: './scripts/php/register.php',
			data	: { 
						name				: name, 
						email				: email, 
						phone				: phone, 
						password			: password ,
						securityQuestion	: securityQuestion,
						humanVerification	: humanVerification,
						equVal				: equVal, 
						lang				: currentLang 
					},
			dataType: "json"
		});
			
		request.done(function(data,textStatus,jqXHR) {
			if ( data.state == 'success' ) {
				message_box.show_message(data.title, data.message); //messagebox.js
				hideRegisterForm(); //overlay.js
				$('#login_message').addClass('success').html("<span style='display:block'>"+name+"</span><span style='display:block'>"+email+"</span><span style='display:block'>"+phone+"</span><hr style='width:50%'/><span style='display:block'><a href='index.php?caller=logout' id='logout'><i><b><u>Logout</u></b></i></a></span>");
				$('#logout').bind('contextmenu click', function(event){
					event.preventDefault();
					window.location.assign("index.php?caller=logout");
					return false;
				});
					
				$('#login_form').slideUp('slow');
				$('#login_message').slideDown('slow');
				$('#userLink').fadeIn('slow');
				$('#customerName').val(name).attr('readonly','readonly');
				$('#customerEmail').val(email).attr('readonly','readonly');
				$('#customerPhone').val(phone).attr('readonly','readonly');
			}
			else if ( data.state == 'fail' ) {
				message_box.show_message(data.title, data.message); //messagebox.js
			}
			else {
				message_box.show_message('<u>Futsal-Time</u><br /><u><b>FATAL ERROR</b></u>',
											"Some error occured while registering.<br /><br />If the error persists please contact <i>support@futsal-time.com</i><br />This really shouldn\'t... could\'t have happened...!" + 
											"<br /><br /><br />Type : <u>" + jqXHR.status + "</u>" +
											"<br />Desciption: <u> Virtual Error</u><br />"+
											"<br /><br />" + jqXHR.responseText + "<br />"); //messagebox.js
			}
		});
			
		request.fail(function(jqXHR, textStatus, errorThrown) {
			message_box.show_message('<u>Futsal-Time</u>',
									"Some error occured while registering.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
									"<br /><u>Type</u> : " + textStatus +
									"<br /><u>Desciption</u> : " + errorThrown +
									"<br /><br />" + jqXHR.responseText); //messagebox.js
		});
	}
	
}

function hideRegisterForm()
{
	$('#register_form_visible').val(0);
	$('#register_box').animate({'top':'-800px'},500,function() {
		$('#overlay').fadeOut('fast');
		$(".error").html('');
		$('#name').val('');
		$('#email').val('');
		$('#emailAgain').val('');
		$('#phone').val('');
		$('#phoneAgain').val('');
		$('#password_id').val('');
		$('#password_text_id').val('');
		$('#humanVerification').val('');
	}).css({'display':'hidden'});
}