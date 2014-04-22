var timeout;
var offset = 0;

function getSchedule(date) {
	var date_orig = date;
	//alert(date);
	var format_day = date.substr(0,2);
	// alert(format_day);
	var format_mon = date.substr(3,2);
	// alert(format_mon);
	var format_year = date.substr(6,4);
	// alert(format_year);
	date = format_year+'-'+format_mon+'-'+format_day;
	// alert(date);
	if ( 'undefined' === typeof(timeout) ) ; else clearTimeout(timeout);
	timeout = setTimeout(function() {
			getSchedule(date_orig);
		},
		parseInt($('input[type=hidden]#rr').val(), 10)
	);
	
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/schedule.php',
		data	: { date: date }
	});
			
	request.done(function(data,textStatus,jqXHR) {
		var height = $(window).height()-170;
		$("#schedule").height(height).html(data);
		
		var numFields = $('#fnum').val();
		var rec_width = ( (schedule_width-30-55) - (numFields*10) ) / numFields;
		$('.fields').css('width',schedule_width-30);
		$('.detailsSection').css('width',schedule_width-30);
		$('.customScr').css('width', rec_width);
		
		var today = new Date();
		var day = today.getDate();
		if ( day < 10 ) day = '0'+day;
		var mon = today.getMonth() + 1;
		if ( mon < 10 ) mon = '0'+mon;
		var year = today.getFullYear();
		var todayStr = year+'-'+mon+'-'+day;
		
		if ( todayStr == date ) {
			var hour = today.getHours();
			var min = today.getMinutes();
					
			if ( min < 15 ) { 
				min = '00';
			} 
			else if ( min < 30 ) {
				if ( $('input[type=hidden]#sfM').val() == '15' ) min = '15';
				else min = '00';
			} 
			else if ( min < 45 ){
				if ( $('input[type=hidden]#sfM').val() == '15' ||  $('input[type=hidden]#sfM').val() == '30' ) min = '30';
				else min = '00';
			} 
			else {
				if ( $('input[type=hidden]#sfM').val() == '15' ) min = '45';
				else if ( $('input[type=hidden]#sfM').val() == '30' ) min = '30';
				else { min = '00'; hour++; }
			}
			if ( hour < 10 ) hour = '0'+hour;
	
			var id = todayStr+'_'+hour+'-'+min+'-00';
			if ( offset == 0 ) {
				if ( $('#'+id).length > 0 )
					$('#schedule').animate(
											{
												scrollTop: $('#'+id).position().top - 150
											}, 
											2000
					);
				else
					$('#schedule').animate(
											{
												scrollTop: 0
											}, 
											2000
					);
				offset++;
			}
		}
		else {
			if ( offset == 0 ) {
				$('#schedule').animate(
										{
											scrollTop: 0
										}, 
										2000
				);
				offset++;
			}
		}
		$('#dtTitle').html(date_orig);
	});
			
	request.fail(function(jqXHR, textStatus, errorThrown) {
		message_box.show_message('<u>Futsal-Time</u>',
								"Some error occured while retrieving data.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
								"<br /><u>Type</u> : " + textStatus +
								"<br /><u>Desciption</u> :" + errorThrown +
								"<br /><br />" + jqXHR.responseText); //messagebox.js
	});	
}

function sendScheduleBySms(arenaNick) {
	var mobile = prompt("Enter mobile to send the day schedule by SMS",'');
	if ( mobile == null ) {
		alert('Mobile phone number was invalid');	
		return ;
	}
	else {
		mobile = mobile.trim();
		mobile = mobile.replace(/ |-/g,"");
	}

	var match = /^(99|96|97)[0-9]{6}$/.test(mobile);
	if ( !match ) {
		alert('Mobile phone number was invalid');	
		return ;
	}
	
	var dateObj = $('#datepicker').datepicker('getDate');
	var dateText = $.datepicker.formatDate('dd-mm-yy DD', dateObj);
	
	
	//alert(date);
	var format_day = dateText.substr(0,2);
	// alert(format_day);
	var format_mon = dateText.substr(3,2);
	// alert(format_mon);
	var format_year = dateText.substr(6,4);
	// alert(format_year);
	date = format_year+'-'+format_mon+'-'+format_day;
	
	var ajaxArgs = 'sqlDate='+date+'&norDate='+dateText+'&nick='+arenaNick+'&mobile='+mobile;
	sendSMS(ajaxArgs, 'sendScheduleSMS');
}