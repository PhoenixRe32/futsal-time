function showDetails(el, i, status) {
	if ( $(el).hasClass('selected') == true || status == -1 ) {
		clear();
		return;
	}
	clear();
	if ( 'undefined' === typeof(timeout) ) ; else clearTimeout(timeout);
	
	var section = el.id.substr(0,19)+'_details';
	var date = el.id.substr(8,2) + "-" + el.id.substr(5,2) + "-" + el.id.substr(0,4);
	var time = el.id.substr(11,2) + ":" + el.id.substr(14,2);
	var resHtml = '';
	
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/getDetails.php',
		data	: {
					date	: date, 
					time	: time,
					field	: i,
					status	: status
				},
		dataType:	"json"
	});
			
	request.done(function(data,textStatus,jqXHR) {
		if ( data.state == 'success' ) {
			$('#'+section).html(data.html);
			
			$('#game_type2').on('click',function(event) {
				$('#game_size2').attr('disabled',true);
				$('#game_size3').attr('disabled',true);
			});
			$('#game_type1').on('click',function(event) {
				$('#game_size2').attr('disabled',false);
				$('#game_size3').attr('disabled',false);
			});
				
			$('#game_size2').on('click',function(event) {
				$('#game_type2').attr('disabled',true);
			});
			$('#game_size3').on('click',function(event) {
				$('#game_type2').attr('disabled',true);
			});
			
			$('#game_size1').on('click',function(event) {
				$('#game_type2').attr('disabled',false);
			});
			
			$('#'+section).show('fast', function() {});
			$('#'+el.id).addClass('selected');			
		}
		else {
			message_box.show_message('<u>Futsal-Time</u>',
							"Some error occured while retrieving the info.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
							"<br /><u>Type</u> : " + textStatus +
							"<br /><br />" + jqXHR.responseText + "<br />"); //messagebox.js
		}
	});
	
	request.fail(function(jqXHR, textStatus, errorThrown) {
		message_box.show_message('<u>Futsal-Time</u>',
							"The request failed.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
							"<br /><u>Type</u> : " + textStatus +
							"<br /><u>Desciption</u> : " + errorThrown +
							"<br /><br />" + jqXHR.responseText); //messagebox.js
	});
}

function clear(){
	if ( 'undefined' === typeof(timeout) ) ; else clearTimeout(timeout);
	
	$('.game').removeClass('selected');
	$('.detailsSection').hide('fast');
	$('.detailsSection').html('');
	
	// var dateObj = $('#datepicker').datepicker('getDate');
	// var dateText = $.datepicker.formatDate('dd-mm-yy DD', dateObj);
	timeout = setTimeout(function() {
			getSchedule($('#dtTitle').html());
		},
		parseInt($('input[type=hidden]#rr').val(), 10)
	);
};

function showInterest(date, time) {
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/getInterest.php',
		data	: {
					date	: date, 
					time	: time
				},
		dataType:	"json"
	});
			
	request.done(function(data,textStatus,jqXHR) {
		message_box.show_message('<u>Interest Shown('+date.substr(8,2)+'-'+date.substr(5,2)+'-'+date.substr(0,4)+' @ '+time.substr(0,5)+')</u>',data.info);
	});

	request.fail(function(jqXHR, textStatus, errorThrown) {
		message_box.show_message('<u>Futsal-Time</u>',
							"Some error occured while retrieving the indo.<br /><br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
							"<br /><u>Type</u> : " + textStatus +
							"<br /><u>Desciption</u> : " + errorThrown +
							"<br /><br />" + jqXHR.responseText); //messagebox.js
	});
}