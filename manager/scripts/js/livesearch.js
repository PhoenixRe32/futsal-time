/*
event.keyCode == 8 || event.keyCode == 46 ||  
( event.keyCode > 47 && event.keyCode < 58 )||
( event.keyCode > 95 && event.keyCode < 106 )
*/
function livesearch(el, about, event, arenaID) {
	if ( event.keyCode < 58 || ( event.keyCode > 95 && event.keyCode < 106 ) ) {		
		var keyword = el.value.trim();
			
		if ( keyword.length < 3 ) {
				$('#livesearch_results1').html('');
				$('#livesearch_results2').html('');
		}
		else {
			var request = $.ajax({
				type	: 'POST',
				url		: './scripts/php/livesearch.php',
				data	: { 
							keyword : keyword, 
							about : about, 
							arenaID : arenaID 
						  },
				datatype: "html"				
			});
			
			request.done(function(data, textStatus, jqXHR) {
				if ( about == 'customer' ) {
					$('#customer_phone_errorloc').html('');
					$('#livesearch_results1').html(data);
				}
				else if ( about == 'opponent' ) {
					$('#opponent_phone_errorloc').html('');
					$('#livesearch_results2').html(data);
				}
			});
			
			request.fail(function(jqXHR, textStatus, errorThrown) {
				//$('#display_result_data').html(textStatus+' '+errorThrown).show();
				alert(errorThrown);
			});
		}
	}
	else {
		if ( about == 'customer' ) {
			$('#livesearch_results1').html('');
			$('#customer_phone_errorloc').html('Invalid character');
		}
		else if ( about == 'opponent' ) {
			$('#livesearch_results2').html('');
			$('#opponent_phone_errorloc').html('Invalid character');
		}
		return;
	}
	
}

function fillInfo(about, name, email, phone) {
	$('#livesearch_results1').html('');
	$('#livesearch_results2').html('');
	
	phone = phone.replace('<b>','');
	phone = phone.replace('</b>','');
	
	if ( about == 'customer' ) {
		$('#customerName').val(name);
		$('#customerEmail').val(email);
		$('#customerPhone').val(phone);
	}
	else if ( about == 'opponent' ) {
		$('#opponentName').val(name);
		$('#opponentEmail').val(email);
		$('#opponentPhone').val(phone);
	}
}