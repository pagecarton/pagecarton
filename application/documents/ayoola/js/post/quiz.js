//	Class Begins
if( ! ayoola.post ){ ayoola.post = {  }; }
ayoola.post.quiz =
{ 
	container: '',
	jsonObjectFromServerForInit: { },
	counterContainer: document.createElement( 'span' ),
	pagination: true,
	url: '/tools/classplayer/get/object_name/Application_Article_Type_Quiz/',
	ajax: null,
	formData: null,
	counter: {},
	splashScreen: {},
	
	//	Information about the current quiz
	current:
	{
		article_id: null,	//	The unique id of the current quiz
		question: 0,	//	current qustion number
		completed: false	//	current qustion number
	},	
	history: 
	{ 
		questions: {  },	//	Question records
		answers: {  },	//	Answer records
		quiz_correct_options: {  },	//	Correct options for the current 
		scores: {  }	//	Scores
	},
				
	//	Starts a quiz
	init: function( jsonObject )
	{
	//	alert( jsonObject.quiz_question );
		
		//	Record this in the history
		ayoola.post.quiz.history.questions[jsonObject.article_id] = jsonObject; 
		ayoola.post.quiz.history.answers[jsonObject.article_id] = {  }; 
		ayoola.post.quiz.current.article_id = jsonObject.article_id; 
		if( jsonObject.a )
		{
			ayoola.post.quiz.history.answers[jsonObject.article_id] = jsonObject.a; 
		}
		if( jsonObject.quiz_time )
		{
			ayoola.post.quiz.counter = ayoola.countdown.init
			({
				secondsLeft: jsonObject.quiz_time,
			//	secondsLeft: 10,
				container: ayoola.post.quiz.counterContainer,
				callbacks: new Array
				(
					//	Complete test without confirmation
					function(){ ayoola.post.quiz.setCompleted( true ); }					
				)
			}); 
		}
		
		//	Move the the next question
		ayoola.post.quiz.next();
	},
				
	//	Returns the DOM to display a question
	getQuestionNode: function( article_id, question )
	{
	//	alert( question );
		if( ! article_id ){ article_id = ayoola.post.quiz.current.article_id; }
		if( undefined == question ){ question = ayoola.post.quiz.current.question; }
	//	if( ! question ){ question = ayoola.post.quiz.current.question; }
		var history = ayoola.post.quiz.history;
		//	Retrieves the next question
		var quiz_question = history.questions[article_id].quiz_question[question];
		var quiz_option1 = history.questions[article_id].quiz_option1[question];
		var quiz_option2 = history.questions[article_id].quiz_option2[question];
		var quiz_option3 = history.questions[article_id].quiz_option3[question];
		var quiz_option4 = history.questions[article_id].quiz_option4[question];
		var isValidQuestion = function( a )
		{
			//	Find from question list
			var b = history.questions[article_id].quiz_question[a];
			var c = history.questions[article_id].quiz_option1[a];
			var d = history.questions[article_id].quiz_option2[a];
			var e = history.questions[article_id].quiz_option3[a];
			var f = history.questions[article_id].quiz_option4[a];
		//	alert( a );
		//	alert( b );
			//	If no next question is not available, we have completed text
			if( ! b || ! c || ! d || ! e || ! f )
			{
				return false;
			}
			return true;
		}
		
		var getOptionClass = function( option )
		{
	//		if( ! a ){ a = question; }
			var e = 'normalnews boxednews';	
			var d = history.quiz_correct_options[article_id];
		//	var d = history.quiz_correct_options[article_id];
/* 			if( ! d || ! d[question] )
			{
	//			return 'normalnews boxednews';				
			}
 */			
			var c = d ? d[question] : undefined;
			var a = history.answers[article_id][question];			
			if( a == option )
			{
			//	return 'selectednews boxednews';				
				e = 'selectednews boxednews';
			}
			
			
			if( d && d[question] && c == option )
			{
			//	return 'goodnews boxednews';
				e = 'goodnews boxednews';
			}
			else if( d && d[question] && c != option && a == option )
			{
			//	return 'goodnews boxednews';
				e = 'badnews boxednews';
			}
			//	Find from question list
			return e;
		}
		
		//	If no next question is not available, we have completed text
		if( ! isValidQuestion( question ) )
		{
		//	return ayoola.post.quiz.setCompleted();
			//	Dont auto submit again
			return false;
		}
				
		//	Record current question
		ayoola.post.quiz.current.question = question;
/* 
		alert( quiz_question );
		alert( quiz_option1 );
		alert( quiz_option2 );
		alert( quiz_option3 );
 */		var form = ayoola.form.init
		({ 
			name: 'ayoola_post_quiz_' + question,
			id: 'ayoola_post_quiz_' + question,
			fieldsets:
			{
				quiz_questions:
				{
					legend: '<span> <strong>Question ' + ( question + 1 ) + ' of ' + ayoola.post.quiz.history.questions[article_id].quiz_question.length + ':</strong>   </span>'
				}
			},
			elements:
			{
				quiz_questions_html:
				{
					type: 'html', 
					label: ' ', 
					fieldset: 'quiz_questions',
					value: '<span>' + quiz_question + '</span>', 
					callbacks: new Array
					(
						{
							callback: function( e )
							{
							//	alert( 'Not clickable' );
							}, 
							when: 'click'
						}
					)
				},
				quiz_answer_notes:
				{
					type: 'html', 
					label: ' ', 
					fieldset: 'quiz_questions',
					value: ( history.quiz_correct_options[article_id] && history.quiz_correct_options[article_id][question] && ayoola.post.quiz.history.questions[article_id].quiz_answer_notes && ayoola.post.quiz.history.questions[article_id].quiz_answer_notes[question] ) ? '<a href="' + ayoola.post.quiz.history.questions[article_id].article_url + '?x_url=/quiz/post/answer-notes&question_id=' + ayoola.post.quiz.history.questions[article_id].quiz_answer_notes[question] + '">View answer notes</a>' : '', 
					callbacks: new Array
					(
						{
							callback: function( e )
							{
							//	alert( history.quiz_correct_options[article_id][question] );
							}, 
							when: 'click'
						}
					)
				},
				quiz_answer:
				{
					type: 'radio', 
					style: 'display:none;', 
					labelStyle: 'display:inline-block;', 
					name: 'quiz_answer[]', 
				//	label: 'Select the answer from the following options: ', 
					label: ' ', 
					className: ' ', 
					value: ayoola.post.quiz.history.answers[article_id][question] || '', 
					fieldset: 'quiz_questions',
					placeholder: 'Please select the correct answer', 
					callbacks: new Array
					(
/* 						{
							callback: function( e )
							{
								var target = ayoola.events.getTarget( e );

								//	select me
								//	This is used to hide the radio button.
								ayoola.div.selectElement( target );	
							}, 
							when: 'click'
						},
 */						{
							callback: function( e )
							{
								var target = ayoola.events.getTarget( e );
							//	target.form.submit();
								ayoola.post.quiz.history.answers[article_id][question] = target.value;
								
								//	Record current question
						//		ayoola.post.quiz.current.question = question;
						
								
								//	Move to the next question
						//		question++;
							//	alert( target.value );
								ayoola.post.quiz.pagination = true;
							//	ayoola.post.quiz.next( article_id, question + 1 );
							}, 
							when: 'click'
						}
					),
					select: new Array
					(
					{ 
			//			className: getOptionClass( '1' ),
						value: '1', 
						label: '<a name="quiz_option_for_' + question + '" class="' + getOptionClass( '1' ) + '" onClick="ayoola.div.selectElement( this ); this.parentNode.click();" title="Click here to select this option as the answer...">' + quiz_option1 + '</a>'
						
						//	Set classname to badnews if the selection is wrong
					},
					{ 
			//			className: getOptionClass( '2' ),
						value: '2', 
						label: '<a name="quiz_option_for_' + question + '" class="' + getOptionClass( '2' ) + '" onClick="ayoola.div.selectElement( this ); this.parentNode.click();" title="Click here to select this option as the answer...">' + quiz_option2 + '</a>'
//						label: quiz_option2
					},
					{ 
		//				className: getOptionClass( '3' ),
						value: '3', 
						label: '<a name="quiz_option_for_' + question + '" class="' + getOptionClass( '3' ) + '" onClick="ayoola.div.selectElement( this ); this.parentNode.click();" title="Click here to select this option as the answer...">' + quiz_option3 + '</a>'
				//		label: quiz_option3
					},
					{ 
			//			className: getOptionClass( '4' ),
						value: '4', 
						label: '<a name="quiz_option_for_' + question + '" class="' + getOptionClass( '4' ) + '" onClick="ayoola.div.selectElement( this ); this.parentNode.click();" title="Click here to select this option as the answer...">' + quiz_option4 + '</a>'
			//			label: quiz_option4 
					}
					)
				},
				previous:
				{
					type: 'button', 
					hide: ayoola.post.quiz.pagination ? false : true, 
					id: 'quiz_previous_question', 
					value: isValidQuestion( question - 1 ) ? 'Back to no. ' + ( question ) + ''  : null, 
					callbacks: new Array
					(
						{
							callback: function( e )
							{
								var target = ayoola.events.getTarget( e );
								
								//	Move to the previous question
							//	question--;
							//	alert( target.value );
								ayoola.post.quiz.next( article_id, question - 1 );
								if( e.preventDefault ){ e.preventDefault(); }
							}, 
							when: 'click'
						}
					)
				},
				submit:
				{
					type: 'submit', 
					hide: ayoola.post.quiz.pagination ? false : true, 
					id: 'quiz_submit_question', 
					value: 'Submit', 
					callbacks: new Array
					(
						{
							callback: function( e )
							{
								var target = ayoola.events.getTarget( e );
								
								//	Move to the previous question
							//	question++;
							//	alert( target.value );
						//		ayoola.post.quiz.next( article_id, question + 1 );
								if( e.preventDefault ){ e.preventDefault(); }
								return ayoola.post.quiz.setCompleted();
							}, 
							when: 'click'
						}
					)
				},
				next:
				{
					type: 'button', 
					hide: ayoola.post.quiz.pagination ? false : true, 
					value: isValidQuestion( question + 1 ) ? 'Proceed to no. ' + ( question + 2 ) + '' : null, 
				//	value: 'Go to Question ' + ( question + 1 ), 
					id: 'quiz_next_question', 
					callbacks: new Array
					(
						{
							callback: function( e )
							{
								var target = ayoola.events.getTarget( e );
								
								//	Move to the previous question
							//	question++;
							//	alert( target.value );
								ayoola.post.quiz.next( article_id, question + 1 );
								if( e.preventDefault ){ e.preventDefault(); }
							}, 
							when: 'click'
						}
					)
				}
			},
			callbacks: new Array()
		});
//		alert( form.innerHTML );
		return form;
	},
	
	//	starts the current quiz
	next: function( article_id, question )
	{
		var next = ayoola.post.quiz.getQuestionNode( article_id, question );
		if( next )
		{
			ayoola.post.quiz.setToContainer( next, ayoola.post.quiz.container );
		}
		else
		{
			return ayoola.post.quiz.setCompleted();
		}
	},
		
	setCompleted: function( noConfirmation )
	{
	//	confirm( 'Do you want to submit this test?' );
		//	Show all the questions and answer
		var article_id = ayoola.post.quiz.current.article_id;
		var questions = ayoola.post.quiz.history.questions[article_id].quiz_question;
		
		var submit = function()
		{
			//	Don't submit twice
			if( ayoola.post.quiz.current.completed )
			{ 
				alert( 'ERROR: Test has already been completed.' );
				return false; 
			}
			if( ! noConfirmation && ! confirm( 'Do you want to submit this test?' ) ){ return false; }
			var uniqueNameForAjax = "ayoola_post_quiz_request";
			
			//	Build the answer list
			ayoola.post.quiz.formData = '';
			ayoola.post.quiz.formData += 'article_url=' + ayoola.post.quiz.history.questions[article_id].article_url;
			for( var b = 0; b < questions.length; b++ )
			{
				ayoola.post.quiz.formData += '&' + b + '=' + ( ayoola.post.quiz.history.answers[article_id][b] || '' );
			}
	//		alert( ayoola.post.quiz.formData );
			
		//	ayoola.xmlHttp.fetchLink( ayoola.post.quiz.url, uniqueNameForAjax, ayoola.post.quiz.formData );
			var xx = ayoola.xmlHttp.fetchLink( { url: ayoola.post.quiz.url, id: uniqueNameForAjax, data: ayoola.post.quiz.formData, skipSend: true } );
		//	alert( arguments.length );
			var ajax = ayoola.post.quiz.ajax = ayoola.xmlHttp.objects[uniqueNameForAjax];
			
			//	return json
			ajax.setRequestHeader( 'AYOOLA-PLAY-MODE', 'JSON' );
			//	alert( ajax );	
			//	alert( xx );	
			//	alert( ayoola.xmlHttp.isReady( ajax ) );	
			var ajaxCallback = function()
			{
			//	alert( ajax );
			//	alert( ajax.readyState ); 
			//	alert( ajax.status ); 
				if( ayoola.xmlHttp.isReady( ajax ) )
				{
					var span = document.createElement( 'span' );
				//	span.appendChild( document.createTextNode( 'Test Completed' ) );
					var h = document.createElement( 'h2' );
					h.appendChild( document.createTextNode( 'Test Completed' ) );
 	//				h.focus();
	//				h.scrollIntoView();
			//		return ayoola.post.quiz.setToContainer( ajax.responseText, ayoola.post.quiz.container );
			//		alert( ajax.responseText );

 					span.appendChild( h );
			//		alert( ajax.responseText );
					try
					{
						var response = JSON.parse( ajax.responseText );
					}
					catch( e )
					{
						alert( e ); //error in the above string(in this case,yes)! 						
						alert( ajax.responseText );
						
						// An error has occured, handle it, by e.g. logging it
						console.log( e );
					}
								//		
				//	alert( response );
					if( ajax.responseText )
					{
						if( response.quiz_correct_option )
						{
							var span1 = document.createElement( 'span' );
							var percentage = Math.floor( ( response.quiz_score / questions.length ) * 100 );
							span1.innerHTML = '<p>Your Score: ' + response.quiz_score + '/' + questions.length + ' (' + percentage + '%)</p>';
							span.appendChild( span1 );
							ayoola.post.quiz.history.scores[article_id] = response.quiz_score;
					//		p.scrollIntoView();
						}
						if( response.quiz_correct_option )
						{
							var span2 = document.createElement( 'span' );
							span2.innerHTML = '<p>Please review the questions to find out what the correct options are. Use the following color codes to navigate the corrections.</p>\
							<p><span class="goodnews boxednews">Correct option</span> <span class="badnews boxednews">Incorrect selection</span> <span class="boxednews">Other options</span> <span class="selectednews boxednews">New selection</span></p>';
							span.appendChild( span2 );
							ayoola.post.quiz.history.quiz_correct_options[article_id] = response.quiz_correct_option;
						}
					}
					span.appendChild( showAllQuestions() );
			//		showAllQuestions();
				//	alert( ajax.responseText ); 
				//	alert( "Template Saved." ); 
					ayoola.post.quiz.setToContainer( span, ayoola.post.quiz.container );
					
					//	stop countdown
					ayoola.post.quiz.counter.stop();
					
					//	Close the splash screen
					ayoola.post.quiz.splashScreen.close ? ayoola.post.quiz.splashScreen.close() : null; 
					
				} 
			}
		//	ajaxCallback();
			ayoola.events.add( ajax, "readystatechange", ajaxCallback );
			
			//	Send ajax request
			ajax.send( ayoola.post.quiz.formData );
		//	document.getElementById( 'ayoola_post_categories' ).innerHTML
			
		//	ayoola.post.quiz.setToContainer( document.createTextNode( 'Submiting test...' ), ayoola.post.quiz.container );
		
			//	Set a splash screen to indicate that we are loading.
			var splash = ayoola.spotLight.splashScreen();
			ayoola.post.quiz.splashScreen = splash;
			
			ayoola.post.quiz.current.completed = true;
		}
		if( noConfirmation ){ submit(); }
		var showAllQuestions = function()
		{
			var span = document.createElement( 'span' );
			
			//	Switch off pagination
			ayoola.post.quiz.pagination = false;
			for( var b = 0; b < questions.length; b++ )
			{
			//	alert( b );
			//	b = b == 0 ? undefined : b;
				var next = ayoola.post.quiz.getQuestionNode( article_id, b );
				if( next )
				{
					span.appendChild( next );
				}
			}
			return span;
		}
		var span = document.createElement( 'span' );
		var h = document.createElement( 'h1' );
		h.innerHTML = 'Please review all the questions before final submission';
		span.appendChild( h );
		span.appendChild( showAllQuestions() );
	//	var span = showAllQuestions();
		var element = document.createElement( 'input' );
		element.setAttribute( 'type', 'button' );
		element.setAttribute( 'value', 'Submit' );
		span.appendChild( element );
		ayoola.events.add( element, 'click', submit );
		ayoola.post.quiz.setToContainer( span, ayoola.post.quiz.container );
	//	showAllQuestions();
	//	alert( span.innerHTML );
		
	},
		
	//	Sets a view to the container
	setToContainer: function( view )
	{
		//	Header 
		var a = ayoola.div.setToContainer( ayoola.post.quiz.counterContainer, ayoola.post.quiz.container );
		var b = ayoola.div.setToContainer( view, ayoola.post.quiz.container, true );
		var c = document.createElement( 'span' );
		var d = b.parentNode;
		d.appendChild( c );
		c.appendChild( a );
		c.appendChild( b );
		c.scrollIntoView();
		c.focus();
	}
}
