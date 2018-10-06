//	Include the neccessary css, js files
//ayoola.files.loadJsObjectCss( 'div' );
//ayoola.files.loadJsObject( 'events' );

//	Class Begins
ayoola.countdown =
{
/* 	https://mindgrader.com/tutorials/1-how-to-create-a-simple-javascript-countdown-timer
 */	// set the date we're counting down to
//	target_date: new Date("Aug 15, 2019").getTime();
	datetime: 
	{ 
		year: null,
		month: null,
		day: null,
		hours: null,
		minutes: null,
		seconds: null,
	}, //	The date to countdown to
/* 	date: 
	{ 
		year: 2013,
		month: 01,
		day: 01,
		hours: 0,
		minutes: 0,
		seconds: 1,
	}, //	The date to countdown to
 */	
	//	Number of seconds to countdown to
	//	This variable is tried before we use datetime
	secondsLeft: null, 
		
	 // get tag element
	container: null,
	
	 // get tag element
	callbacks: new Array,
	
	// Turn an input text to a multiple input for the same variable 
	init: function( timerObject  )
	{
		//	Find out if any of the timer variables were sent in the argument
		var datetime = timerObject.datetime || ayoola.countdown.datetime;
		var secondsLeftx = timerObject.secondsLeft || ayoola.countdown.secondsLeft;
		var container = timerObject.container || ayoola.countdown.container;
		var callbacks = timerObject.callbacks || ayoola.countdown.callbacks;
		
		//  we use time first 
		if( ! secondsLeftx )
		{
			// find the amount of "seconds" between now and target
			var target = new Date( datetime.year, datetime.month, datetime.day, datetime.hours, datetime.minutes, datetime.seconds ).getTime();
			secondsLeftx = ( target - ( new Date().getTime() ) ) / 1000;
		}
	//	alert( secondsLeftx );

		// variables for time units
		var years, months, days, hours, minutes, seconds;

		// get tag element
	//	var countdown = document.getElementById("countdown");		
		var countdown = function()
		{
			var content = '';
			secondsLeft = secondsLeftx;
			// do some time calculations
			//	alert( secondsLeft );
			//	alert( secondsLeft / 946080000 );
			
			if( secondsLeft > 946080000 )
			{
				years = parseInt( secondsLeft / 946080000 );
				secondsLeft = parseInt( secondsLeft % 946080000 );
				content += ' ' + years + ' yr ';
			}
	//			alert( years );
	
			// do some time calculations
			if( secondsLeft > 2592000 )
			{
				months = parseInt( secondsLeft / 2592000 );
				secondsLeft = parseInt( secondsLeft % 2592000 );
				content += ' ' + months + ' m ';
			}

			// do some time calculations
			if( secondsLeft > 86400 )
			{
				days = parseInt( secondsLeft / 86400 );
				secondsLeft = parseInt( secondsLeft % 86400 );
				content += ' ' + days + ' days ';
			}

			if( secondsLeft > 3600 )
			{
				hours = parseInt( secondsLeft / 3600 );
				secondsLeft = parseInt( secondsLeft % 3600 );
				content += ' ' + hours + ' hr ';
			}

			if( secondsLeft > 60 )
			{
				minutes = parseInt( secondsLeft / 60 );
				content += ' ' + minutes + ' min ';
			}
			seconds = parseInt( secondsLeft % 60 );
		//	alert( seconds );
			secondsLeftx--;
			// format countdown string + set tag value
			if( seconds )
			{
				content += ' ' + seconds + ' sec ';
			}
	//		alert( secondsLeft );
	//		alert( content );
			if( secondsLeftx < 0 )
			{
				stopCounting();
				
				//	Judgement day
				for( var a = 0; a < callbacks.length; a++ )
				{
					var callback = callbacks[a];
					callback();
				}
				
			}
			ayoola.div.setToContainer( content, container );
		}
		// update the tag every 1 second
		var intervalId = setInterval( countdown, 1000 );
		var stopCounting = function()
		{
			clearInterval( intervalId );
		}
		return { stop: stopCounting };
	//	return container;
	}, 
				
}
