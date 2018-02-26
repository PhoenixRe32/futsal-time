/* 
 * JavaScript code for message box borrowed by the "jQuery message box" snippet
 * by Dejan Jacimovic (http://stuntsnippets.com/author/dejan-jacimovic/)
 * at stuntCODERS (http://stuntsnippets.com/jquery-message-box/).
 * Credit where credit is due. Our thanks.
 */
 
 var message_box = function() {
	var button = '<input type="button" onclick="message_box.close_message();" value="Okay!" />';
	return {
		show_message: function(title, body, ok ) {
			ok = typeof ok !== 'undefined' ? ok : true;
			
			if(jQuery('#message_box').html() === null) {
				if ( ok )
					var message = '<div id="message_box"><h2>' + title + '</h2>' + body + '<br/>' + button + '</div>';
				else
					var message = '<div id="message_box"><h2>' + title + '</h2>' + body + '<br/>' + '</div>';
				jQuery(document.body).append( message );
				jQuery(document.body).append( '<div id="darkbg"></div>' );
				jQuery('#darkbg').show();
				jQuery('#darkbg').css('height', jQuery(document).height());
 
				jQuery('#message_box').css('top', '15%');
				jQuery('#message_box').show('slow');
			} else {
				if ( ok )
					var message = '<h2>' + title + '</h2>' + body + '<br/>' + button;
				else
					var message = '<h2>' + title + '</h2>' + body + '<br/>';
				jQuery('#darkbg').show();
				jQuery('#darkbg').css('height', jQuery(document).height());
 
				jQuery('#message_box').css('top', '15%');
				jQuery('#message_box').show('slow');
				jQuery('#message_box').html( message );
			}
		},
		close_message: function() {
			jQuery('#message_box').hide('fast');
			jQuery('#message_box').html('');
			jQuery('#darkbg').hide();
		}
	}
}();