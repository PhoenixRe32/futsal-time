$(document).ready(function() {
	$('#searchButton').click(function(event) { 
		event.preventDefault();
		//alert($('#daySelector').val()+"\n"+$('#hourSelector').val()+"\n"+$('#arenaSelector').val());
		postSearchParameters();
	});
	
	$('#daySelector, #hourSelector, #arenaSelector').change(function() {
		postSearchParameters();
	});
	
	$('#daySelector, #hourSelector, #arenaSelector').focusin(function(){ $('#search_form_focused').val(1); });
	$('#daySelector, #hourSelector, #arenaSelector').focusout(function(){ $('#search_form_focused').val(0); });
	
	$(document).keyup(function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		if ( $('#search_form_focused').val() == 1 ) {
			if ( code == 13 ) {
				event.preventDefault();
				postSearchParameters();
			}
		}
	});
});

function postSearchParameters()
{
	$('#dtTitle').val('');
	var dbDate = $('#daySelector').val();
	var day = $('#daySelector option:selected').text();
	
	if ( $('#daySelector').val() != -1 &&
	     $('#hourSelector').val() != -1 &&
		 $('#arenaSelector').val() != -1 
		) {
		var request = $.ajax({
			type	: 'POST',
			url		: './scripts/php/search.php',
			data	: { date: $('#daySelector').val(), hour: $('#hourSelector').val(), arena:$('#arenaSelector').val(), lang:getCookie('language') }
		});
				
		request.done(function(data,textStatus,jqXHR) {
			$("#searchResults").html(data);
			$('#dtTitle').val(dbDate.substr(8,2) + '/ ' + dbDate.substr(5,2) + ' ' + day.substr(8) + ' (' + $('#arenaSelector :selected').text() + ')');
		});
				
		request.fail(function(jqXHR, textStatus, errorThrown) {
			message_box.show_message('<u>Futsal-Time</u>',
									"Some error occured while retrieving data.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
									"<br /><u>Type</u> : " + textStatus + 
									"<br /><u>Desciption</u> : " + errorThrown + 
									"<br /><br />" + jqXHR.responseText); //messagebox.js
		});	
	}
}

function buildDayList()
{
	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth()+1;
	var day = today.getDate();
	var date = '';
		
	var weekday=new Array(7);

	var currentLang = getCookie('language');
	if ( currentLang == 'en' ) {
		weekday[1]="Monday";
		weekday[2]="Tuesday";
		weekday[3]="Wednesday";
		weekday[4]="Thursday";
		weekday[5]="Friday";
		weekday[6]="Saturday";
		weekday[7]="Sunday";
	}
	else if ( currentLang == 'gr' ) {
		weekday[1]="Δευτέρα";
		weekday[2]="Τρίτη";
		weekday[3]="Τετάρτη";
		weekday[4]="Πέμπτη";
		weekday[5]="Παρασκευή";
		weekday[6]="Σάβατο";
		weekday[7]="Κυριακή";
	}
	else {
		weekday[1]="Monday";
		weekday[2]="Tuesday";
		weekday[3]="Wednesday";
		weekday[4]="Thursday";
		weekday[5]="Friday";
		weekday[6]="Saturday";
		weekday[7]="Sunday";
	}
	
	var thisDay=today.getDay();

	if(thisDay==0) {
		thisDay=7;	
	}

	var nextweek=0;


	var thisWeek=7-thisDay;
	var iterTimes=thisWeek+8;
	var currentDay=thisDay;//day tha is currently being assignt in the loop

	if ( currentLang == 'en' )
		var htmlCode = "<optgroup label=\"Current Week\">";
	else if ( currentLang == 'gr' ) 
		var htmlCode = "<optgroup label=\"Τρέχουσα Εβδομάδα\">";
	else
		var htmlCode = "<optgroup label=\"Current Week\">";
	
	var iter = 0
	for ( iter = 0; iter < iterTimes; iter++ ) {
		
		if(currentDay==1&&thisWeek<iter) {
			nextweek=1;	
		}
		
			if(nextweek==1) {
				
				htmlCode += "</optgroup>\n";
				if ( currentLang == 'en' )
					htmlCode += "<optgroup label=\"Next Week\">";
				else if ( currentLang == 'gr' ) 
					htmlCode += "<optgroup label=\"Επόμενη Εβδομάδα\">";
				else
					htmlCode += "<optgroup label=\"Next Week\">";
				
				nextweek=0;
						
			}
			
			if(currentDay==7){
				currentDay=1;	
			}else{
				currentDay++;
			}

		var date = year;
		if ( month < 10 )	date = date + "-0" + month;
		else				date = date + "-" + month;
		if ( day < 10 )		date = date + "-0" + day;
		else				date = date + "-" + day;
		
		var displayedDate;
		if ( day < 10 )		displayedDate='\t0' + day;
		else				displayedDate='\t' + day;
		if ( month < 10 )	displayedDate= displayedDate + "/0" + month;
		else				displayedDate= displayedDate + "/" + month;
		
		if(thisDay>7) {
			thisDay=1;
		}
		displayedDate= displayedDate + " - " + weekday[thisDay++];//display date with day
		htmlCode += "<option value=\"" +date+ "\">" + displayedDate;
	
		day++;
		if ( day > 31 ) {
			if ( month == 1 || month == 3 || month == 5 || month == 7 ||
				 month == 8 || month == 10 || month == 12 ) {
				day = 1;
				month++;
				if ( month > 12 ) {
					month = 1;
					year++;
				}
			}
		}
		else if ( day > 30 ) {
			if ( month == 4 || month == 6 || 
				 month == 9 || month == 11 ) {
				day = 1;
				month++;
				if ( month > 12 ) {
					month = 1;
					year++;
				}
			}
		}
		else if ( day > 28 ) {
			if ( month == 2 ) {
				if ( (year - 2012) % 4 == 0 ) {
					if ( day > 29 ) {
						day = 1;
						month++;
						if ( month > 12 ) {
							month = 1;
							year++;
						}
					}
				}
				else {
					day = 1;
					month++;
					if ( month > 12 ) {
						month = 1;
						year++;
					}
				}
			}
		}
	}
	
	htmlCode += "</optgroup>\n";
	htmlCode += "</select>";
	
	return htmlCode;
}
