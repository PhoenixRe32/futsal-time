$(document).ready(function() {
	$('#datepicker').datepicker( {
		minDate: 0,
		maxDate: +15,
		showOn:'focus',
		dateFormat: 'dd/mm/yy - DD',
		onSelect: function(dateText, inst) {
			postSearchParameters(dateText.substr(6,4) + '-' + dateText.substr(3,2) + '-' + dateText.substr(0,2));
		}
	});
	
	$("#datepicker").datepicker("setDate", new Date());
	var dateObj = $('#datepicker').datepicker('getDate');
	var dateText = $.datepicker.formatDate('yy-mm-dd', dateObj);
	postSearchParameters(dateText);
});

function postSearchParameters(sqlDate)
{
	$('#overlay').fadeIn('fast',function() {
		//$('#overlay').html("<img src='./img/loading.gif' alt='Loading' style='position:fixed; top:50%; left:50%; margin-top:-33px; margin-left:-33px;'></img>");
	});
	
	var currentLang = getCookie('language');
	if ( currentLang == null )
		currentLang = 'en';
	var request = $.ajax({
		type	: 'POST',
		url		: './scripts/php/search.php',
		data	: { date: sqlDate, hour: '20:00:00', arena:1, lang:currentLang }
	});
			
	request.done(function(data,textStatus,jqXHR) {
		$('#overlay').fadeOut('fast');
		$("#searchResults").html(data);
	});
			
	request.fail(function(jqXHR, textStatus, errorThrown) {
		$('#overlay').fadeOut('fast');
		message_box.show_message('<u>Futsal-Time</u>',
								"Some error occured while retrieving data.<br />If the error persists please contact <i>support@futsal-time.com</i><br />" +
								"<br /><u>Type</u> : " + textStatus + 
								"<br /><u>Desciption</u> : " + errorThrown + 
								"<br /><br />" + jqXHR.responseText); //messagebox.js
	});
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
	if ( currentLang == null )
		currentLang = 'en';
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
		weekday[6]="Σάββατο";
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
