function changeHistoryPage(pageDiff) {
	var page;
	var lastpage;
	
	$('table[id^=bookingGroup]').each(function() {
		if ( $(this).css('display') == 'table' ) {
			page = $(this).find('#bookingGroupID').val();
			lastpage = $(this).find('#finalBookingGroupID').val();
		}
	});
	
	var newpage = page;
	if ( pageDiff == '+1' )
		newpage++;
	else if ( pageDiff == '-1' )
		newpage--;
	
	if ( newpage > lastpage ) {
		message_box.show_message('<u>Futsal-Time</u>', 'You have reached the end of your bookings!', false); //messagebox.js
		setTimeout(
			function() {
				message_box.close_message();
			},
			2500
		);
	}
	else if ( newpage > 0 ) {//slide, drop, fadeIn fadeOut, show hide
		$('#bookingGroup'+page).fadeOut('slow', function() {
			$('#bookingGroup'+newpage).fadeIn('slow');
		});
		
		/* $('#bookingGroup'+page).hide('slow', function() {
			$('#bookingGroup'+newpage).show('slow');
		}); */
	}
	
	else if ( newpage <= 0 ) {
		message_box.show_message('<u>Futsal-Time</u>', 'These are your latest bookings!', false); //messagebox.js
		setTimeout(
			function() {
				message_box.close_message();
			},
			2500
		);
	}
	
	return;
}