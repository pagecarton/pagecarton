//	Include the neccessary css, js files
//ayoola.files.loadJsObjectCss( 'div' );
//ayoola.files.loadJsObject( 'events' );

//	Class Begins
ayoola.post =
{
	title: null, // The title of the post
	types:
	{ 
		article: 
		{
			content: null,
			backup: null, //	This is here to save state after every submission
			init: function()
			{
				var form = ayoola.post.types.article.backup || ayoola.form.init
				({ 
					elements: 
					{ 
						article_content:
						{
							type: 'textarea', 
							id: 'article_content_article', 
							label: 'Post Content', 
					//		appendedHtml: document.getElementById( 'ayoola_post_categories' ) 
					//						? document.getElementById( 'ayoola_post_categories' ).innerHTML
				//							: '', 
			//				value: '', 
							placeholder: 'Begin to type the article here...', 
						},
						submit:
						{
							type: 'submit', 
							value: 'Continue', 
						}
					},
					callbacks: new Array
					({
							callback: function( e )
							{
							//	alert( e );
								var target = ayoola.events.getTarget( e );
							//	tinyMCE.execCommand( 'mceRemoveControl', true, 'article_content_article' );
							//	if (CKEDITOR.instances["article_content_article"]) { delete CKEDITOR.instances["article_content_article"] };
								if (CKEDITOR.instances["article_content_article"]) { CKEDITOR.instances["article_content_article"].destroy(); } 
		//						tinyMCE.remove();
							//	tinyMCE.destroy();
							
								//	save state
								ayoola.post.types.article.backup = target;
								ayoola.post.restarting.type = false;
								ayoola.post.types.article.content = ayoola.div.getFormValues( target, true );
							//	alert( target.elements['article_content'].value );
							//	alert( ayoola.post.types.article.content );
								ayoola.post.lastCommand();
								if( e.preventDefault ){ e.preventDefault(); }
							}, 
							when: 'submit'
					})
				});
				ayoola.post.setToContainer( form );
		//		alert( form.innerHTML );
		
				//	Insert Rich Text Editor
				if (CKEDITOR.instances["article_content_article"]) { delete CKEDITOR.instances["article_content_article"] };
				if (CKEDITOR.instances["article_content_article"]) { CKEDITOR.instances["article_content_article"].destroy(); } 
				CKEDITOR.replace
				( 
					"article_content_article",
					{
						filebrowserBrowseUrl: "/ayoola/thirdparty/Filemanager/index.php",
					}
				);	

/* 				tinymce.init
				({
				selector: "textarea#article_content_article",theme: "modern",
				external_filemanager_path:"/ayoola/thirdparty/ResponsiveFileManager/",
				filemanager_title:"File Manager" ,
				plugins: [
					"advlist autolink lists link image charmap print preview anchor",
					"searchreplace visualblocks code fullscreen",
					"insertdatetime media table contextmenu paste responsivefilemanager filemanager youtube"
				],
				toolbar: "responsivefilemanager | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | youtube",
				autosave_ask_before_unload: true,
				max_height: 200,
				min_height: 160,
				convert_urls: false,
				image_advtab: true ,
				height : 180
				});
 */				
			}
		}, 
		video: 
		{
			content: null,
			backup: null, //	This is here to save state after every submission
			init: function()
			{
				var form = ayoola.post.types.video.backup || ayoola.form.init
				({ 
					fieldsets: 
					{ 
/* 						video_option_url:
						{
						//	legend: 'video File "URL" Link',
					//		style: 'display:none;', 
							prependedHtml: 
								'\
								<input type="radio" name="video_type" id="video_type_selector_url" value="" title="Public URL link" onClick="document.getElementById( \'video_option_url\' ).style.display=\'\';" />\
								<label for="video_type_selector_url">Embed a video from YouTube or similar service.</label>\
								',
						}
 */					},
					elements: 
					{ 
						video_url:
						{
							type: 'text', 
							label: 'Enter a link to an embeddable video from YouTube or similar service. E.g. http://www.youtube.com/embed/W-Q7RMpINVo', 
							id: 'video_url', 
						//	fieldset: 'video_option_url', 
							placeholder: 'e.g. http://www.youtube.com/embed/W-Q7RMpINVo', 
							appendedHtml: 
								'\
								<input style="" type="button" value="Browse" title="Select or upload file" onClick="ayoola.spotLight.showLinkInIFrame( \'/ayoola/thirdparty/Filemanager/index.php?field_name=video_url\' );" />\
								',
 							callbacks: new Array
							({
								callback: function( e )
								{
									
/* 									//	Clear the public link
									var a = document.getElementById( 'video_path' );
									a ? ( a.value = '' ) : null;
 */								}, 
								when: 'click'
							})
						},
						submit:
						{
							type: 'submit', 
							value: 'Continue', 
						}
					},
					callbacks: new Array
					({
							callback: function( e )
							{
							//	alert( e );
								var target = ayoola.events.getTarget( e );
							
								//	save state
								ayoola.post.types.video.backup = target;
								ayoola.post.restarting.type = false;
								ayoola.post.types.video.content = ayoola.div.getFormValues( target, true );
								ayoola.post.lastCommand();
								if( e.preventDefault ){ e.preventDefault(); }
							}, 
							when: 'submit'
					})
				});
				ayoola.post.setToContainer( form );				
			}
		//		alert( form.innerHTML );
		}, 
		photo: 
		{
		
		}, 
		music: 
		{
		
		}, 
		download: 
		{
			content: null,
			backup: null, //	This is here to save state after every submission
			init: function()
			{
				var form = ayoola.post.types.download.backup || ayoola.form.init
				({ 
					fieldsets: 
					{ 
						download_option_url:
						{
						//	legend: 'Download File "URL" Link',
							style: 'display:none;', 
							prependedHtml: 
								'\
								<input type="radio" name="download_type" id="download_type_selector_url" value="" title="Public URL link" onClick="document.getElementById( \'download_option_url\' ).style.display=\'\'; document.getElementById( \'download_option_path\' ).style.display=\'none\';var a = document.getElementById( \'download_path\' ); a ? ( a.value = \'\' ) : null;" />\
								<label for="download_type_selector_url">Redirect to a public link. This option will make the download button redirect the user to another URL to trigger the download. The download URL need not be a link on this website.</label>\
								',
						},
						download_option_path:
						{
						//	legend: 'Download File "local" Path',
							style: 'display:none;', 
							prependedHtml: 
								'\
								<input type="radio" name="download_type" id="download_type_selector_path" value="" title="Public URL link" onClick="document.getElementById( \'download_option_path\' ).style.display=\'\'; document.getElementById( \'download_option_url\' ).style.display=\'none\';var a = document.getElementById( \'download_url\' ); a ? ( a.value = \'\' ) : null;" />\
								<label for="download_type_selector_path">Outputs a local file to the user. The file must have been previously uploaded to the server, saved (preferably) in a location outside the web root.</label>\
								',
						}
					},
					elements: 
					{ 
						download_url:
						{
							type: 'text', 
							label: 'Download Link', 
							id: 'download_url', 
							fieldset: 'download_option_url', 
							placeholder: 'e.g. http://example.com/path/to/file', 
							appendedHtml: 
								'\
								<input style="" type="button" value="Browse" title="Select or upload file" onClick="ayoola.spotLight.showLinkInIFrame( \'/ayoola/thirdparty/Filemanager/index.php?field_name=download_url\' );" />\
								',
 							callbacks: new Array
							({
								callback: function( e )
								{
									
/* 									//	Clear the public link
									var a = document.getElementById( 'download_path' );
									a ? ( a.value = '' ) : null;
 */								}, 
								when: 'click'
							})
						},
						download_path:
						{
							type: 'text', 
							label: 'Download Path <input style="" type="button" value="Browse" title="Select or upload file" />', 
							id: 'download_path', 
							fieldset: 'download_option_path', 
							placeholder: 'e.g. /var/www/path/to/file', 
/* 							browse: function( e )
							{
								ayoola.spotLight.showLinkInIFrame( '/ayoola/thirdparty/Filemanager/index.php?field_name=download_path&return_full_path=true&directory=APPLICATION_PATH' );
								
								//	Clear the public link
								var a = document.getElementById( 'download_url' );
								a ? ( a.value = '' ) : null;
							}, 
 */							callbacks: new Array
							({
								callback: function( e )
								{
									var target = ayoola.events.getTarget( e );
									ayoola.spotLight.showLinkInIFrame( '/ayoola/thirdparty/Filemanager/index.php?field_name=download_path&return_full_path=true&directory=APPLICATION_DIR' );
									target.blur();
/* 									//	Clear the public link
									var a = document.getElementById( 'download_url' );
									a ? ( a.value = '' ) : null;
 */								}, 
								when: 'click'
							},
							{
								callback: function( e )
								{
									var target = ayoola.events.getTarget( e );
									target.className = target.value ? 'goodnews' : 'badnews';
								}, 
								when: 'mouseout'
							}),
/* 							appendedHtml: 
								'\
								<input style="display:inline-block" type="button" value="Browse" title="Select or upload file" onClick="ayoola.spotLight.showLinkInIFrame( \'/ayoola/thirdparty/Filemanager/index.php?field_name=download_path&return_full_path=true&directory=APPLICATION_PATH\' );" />\
								',
 */						},
						download_version:
						{
							type: 'text', 
							label: 'Version (Optional)', 
							placeholder: 'Download version', 
						},
						download_password:
						{
							type: 'password', 
							label: 'Require a password for this download (Optional)', 
							placeholder: 'Please enter a password', 
						},
						download_options:
						{
							type: 'checkbox', 
							name: 'download_options[]', 
							label: ' ', 
							select: new Array
							({ 
								value: 'require_user_info', 
								label: 'Require user infomation before download' 
							},
							{ 
								value: 'download_notification', 
								label: 'Notify me on every download' 
							})
						},
						submit:
						{
							type: 'submit', 
							value: 'Continue', 
						}
					},
					callbacks: new Array
					({
							callback: function( e )
							{
							//	alert( e );
								var target = ayoola.events.getTarget( e );
							
								//	save state
								ayoola.post.types.download.backup = target;
								ayoola.post.restarting.type = false;
								ayoola.post.types.download.content = ayoola.div.getFormValues( target, true );
								ayoola.post.lastCommand();
								if( e.preventDefault ){ e.preventDefault(); }
							}, 
							when: 'submit'
					})
				});
				ayoola.post.setToContainer( form );				
			}
		//		alert( form.innerHTML );
		}, 
		poll: 
		{
			content: null,
			backup: null, //	This is here to save state after every submission
			init: function()
			{
				var form = ayoola.post.types.poll.backup || ayoola.form.init
				({ 
					fieldsets: 
					{ 
						poll_option_set:
						{
							legend: 'Poll Option 1',
							appendedHtml: '<input type="button" class="goodnews" value="+" title="Add a new poll option" href="javascript:" onClick="ayoola.form.cloneElements( { element:\'poll_option_set\', elementOptions: new Array( { label: \'Poll Option\', counter: 1 } ), triggerElement: this } );" />', 
						}
					},
					elements: 
					{ 
						poll_question:
						{
							type: 'text', 
							id: 'poll_question_id', 
							label: 'Poll Question', 
							placeholder: 'Begin to type the poll question here...', 
						},
						poll_options:
						{
							type: 'text', 
							name: 'poll_options[]', 
							id: 'poll_option_id', 
							fieldset: 'poll_option_set', 
							label: 'First Option', 
							placeholder: 'Possible answer to the poll question', 
						},
						poll_option_preset_votes:
						{
							type: 'text', 
							name: 'poll_option_preset_votes[]', 
							id: 'poll_options_preset_votes_id', 
							fieldset: 'poll_option_set', 
							placeholder: 'No. of votes to preset for this poll option', 
							label: 'Preset votes', 
						},
						submit:
						{
							type: 'submit', 
							value: 'Continue', 
						}
					},
					callbacks: new Array
					({
						callback: function( e )
						{
						//	alert( e );
							var target = ayoola.events.getTarget( e );
							ayoola.post.types.poll.content = ayoola.div.getFormValues( target, true );
							
							//	save state
							ayoola.post.types.poll.backup = target;
							ayoola.post.restarting.type = false;
						//	alert( target.elements['article_content'].value );
						//	alert( ayoola.post.types.article.content );
							ayoola.post.lastCommand();
							if( e.preventDefault ){ e.preventDefault(); }
						}, 
						when: 'submit'
					})
				});
				ayoola.post.setToContainer( form );
			}
		}, 
		subscription: 
		{
			content: null,
			backup: null, //	This is here to save state after every submission
			init: function()
			{
				var form = ayoola.post.types.subscription.backup || ayoola.form.init
				({ 
					fieldsets: 
					{ 
						price_list:
						{
							legend: 'Option 1',
							appendedHtml: '<input type="button" class="goodnews" value="+" title="Add a new product or service pricing option" href="javascript:" onClick="ayoola.form.cloneElements( { element:\'price_list\', elementOptions: new Array( { label: \'Product or Service\', counter: 1 } ), triggerElement: this } );" />', 
						}
					},
					elements: 
					{ 
						item_price:
						{
							type: 'text', 
							name: 'item_price', 
							id: 'item_price_id', 
							label: 'Price for Item', 
							placeholder: 'Enter item price here', 
						},
						no_of_items_in_stock:
						{
							type: 'text', 
							name: 'no_of_items_in_stock', 
							id: 'no_of_items_in_stock_id', 
							label: 'Number of items in stock', 
							placeholder: 'Enter Number of items in stock here', 
						},
						item_old_price:
						{
							type: 'text', 
							name: 'item_old_price', 
							id: 'item_price_id', 
							label: 'Old price (Used in calculating savings)', 
							placeholder: 'Enter item old price here', 
						},
						submit:
						{
							type: 'submit', 
							value: 'Continue', 
						}
					},
					callbacks: new Array
					({
						callback: function( e )
						{
						//	alert( e );
							var target = ayoola.events.getTarget( e );
							ayoola.post.types.subscription.content = ayoola.div.getFormValues( target, true );
							
							//	save state
							ayoola.post.types.subscription.backup = target;
							ayoola.post.restarting.type = false;
						//	alert( target.elements['article_content'].value );
						//	alert( ayoola.post.types.article.content );
							ayoola.post.lastCommand();
							if( e.preventDefault ){ e.preventDefault(); }
						}, 
						when: 'submit'
					})
				});
				ayoola.post.setToContainer( form );
			}
		}, 
		quiz: 
		{
			content: null,
			backup: null, //	This is here to save state after every submission
			init: function()
			{
				var form = ayoola.post.types.quiz.backup || ayoola.form.init
				({ 
					fieldsets: 
					{ 
						quiz_questions:
						{
							legend: 'New Question',
							appendedHtml: '<input type="button" class="goodnews" value="+" title="Add a new question" href="javascript:" onClick="ayoola.form.cloneElements( { element:\'quiz_questions\', elementOptions: new Array( { label: \'Question \', counter: 1 } ), triggerElement: this } );" />'
						}
					},  
					elements: 
					{ 
						quiz_time:
						{
							type: 'select', 
							label: 'Quiz Maximum Time', 
							value: '600', 
							placeholder: 'How long should this quiz last?',
							fieldset: 'quiz_settings',
							select: new Array
							(
							{ 
								value: '300', 
								label: '5 Mins' 
							},
							{ 
								value: '600', 
								label: '10 Mins' 
							},
							{ 
								value: '1200', 
								label: '20 Mins' 
							},
							{ 
								value: '1800', 
								label: '30 Mins' 
							},
							{ 
								value: '3600', 
								label: '1 Hour' 
							}
							)
						},
						quiz_question:
						{
							type: 'text', 
							name: 'quiz_question[]', 
							label: 'Question', 
							fieldset: 'quiz_questions',
							placeholder: 'Enter a question', 
						},
						quiz_option1:
						{
							type: 'text', 
							name: 'quiz_option1[]', 
							label: 'Option 1', 
							fieldset: 'quiz_questions',
							placeholder: 'Enter the first possible answer to the question', 
						},
						quiz_option2:
						{
							type: 'text', 
							name: 'quiz_option2[]', 
							label: 'Option 2', 
							fieldset: 'quiz_questions',
							placeholder: 'Enter the second possible answer to the question', 
						},
						quiz_option3:
						{
							type: 'text', 
							name: 'quiz_option3[]', 
							label: 'Option 3', 
							fieldset: 'quiz_questions',
							placeholder: 'Enter the third possible answer to the question', 
						},
						quiz_option4:
						{
							type: 'text', 
							name: 'quiz_option4[]', 
							label: 'Option 4', 
							fieldset: 'quiz_questions',
							placeholder: 'Enter the fourth possible answer to the question'
						},
						quiz_correct_option:
						{
							type: 'select', 
							name: 'quiz_correct_option[]', 
							label: 'Correct Option', 
							fieldset: 'quiz_questions',
							placeholder: 'Select correct option to the question', 
							select: new Array
							(
							{ 
								value: '1', 
								label: 'Option 1' 
							},
							{ 
								value: '2', 
								label: 'Option 2' 
							},
							{ 
								value: '3', 
								label: 'Option 3' 
							},
							{ 
								value: '4', 
								label: 'Option 4' 
							}
							)
						},
						submit:
						{
							type: 'submit', 
							value: 'Continue', 
						}
					},
					callbacks: new Array
					({
						callback: function( e )
						{
						//	alert( e );
							var target = ayoola.events.getTarget( e );
							ayoola.post.types.quiz.content = ayoola.div.getFormValues( target, true ); 
							
							//	save state
							ayoola.post.types.quiz.backup = target;
							ayoola.post.restarting.type = false;
							
						//	alert( target.elements['article_content'].value );
						//	alert( ayoola.post.types.article.content );
							ayoola.post.lastCommand();
							if( e.preventDefault ){ e.preventDefault(); }
						}, 
						when: 'submit'
					})
				});
				ayoola.post.setToContainer( form ); 
			}
		}
	}, // Allowed types of post
	type: null, // Post default type
	lastCommand: null, // Last command to fall back to
	container: null, // container for post elements
	formData: '', // container for post elements
	generalData: null, // form values for optional fields
	generalDataBackUp: null, // form values for optional fields
	ajax: null, // Ajax
	restarting: // set to true if we are trying to edit content
	{ 
		type: true,
		generalData: true
	},
	url: '/tools/classplayer/get/object_name/Application_Article_Generator/', // Ajax
	formElements: 
	{ 
		type: 
		{ 
		}, 
		
		title: 
		{ 
		} 
	},
		
	//	inits a new post
	init: function()
	{
	//	alert( this );
		ayoola.post.formData = '';
		ayoola.post.lastCommand = ayoola.post.init;
	//	alert( ayoola.post.type );
		if( ! ayoola.post.type )
		{ 
			var form = ayoola.post.setType();
			ayoola.post.setToContainer( form );
			return false;
		}
		// Check if type is enabled.
		if( ! ayoola.post.types[ayoola.post.type] || ! ayoola.post.types[ayoola.post.type].init )
		{ 
			ayoola.spotLight.popUp
			( 
				'<div style="background-color:white;text-align:center;padding:1em;">' + ayoola.post.type + ' has not been enabled yet.</div>' 
			);
			return false;
		}
		
		
		
		ayoola.post.formData += 'article_type=' + encodeURIComponent( ayoola.post.type );
		if( ! ayoola.post.title )
		{ 
			var form = ayoola.post.setTitle();
			ayoola.post.setToContainer( form );
			return false;
		}
		ayoola.post.formData += '&article_title=' + encodeURIComponent( ayoola.post.title );
	//	alert( ayoola.post.type );
	//	alert( ayoola.post.types[ayoola.post.type] );
	//	alert( ayoola.post.types[ayoola.post.type].content );
		if( ! ayoola.post.types[ayoola.post.type].content || ayoola.post.restarting.type )
		{ 
		//	alert( ayoola.post.types[ayoola.post.type].init );
			ayoola.post.types[ayoola.post.type].init();
			return false;
		}
		ayoola.post.formData += '&' + ayoola.post.types[ayoola.post.type].content;
		
		//	Optional fields
		if( ! ayoola.post.generalData || ayoola.post.restarting.generalData )
		{ 
			var form = ayoola.post.generalDataBackUp || ayoola.form.init
			({ 
				elements: 
				{ 
					submit: 
					{ 
						type: 'submit', 
						label: '', 
						value: 'Save', 
						callbacks: new Array(),
						placeholder: '', 
					} 
				},
				callbacks: new Array
				(
					{
						callback: function( e )
						{
							var target = ayoola.events.getTarget( e );
							ayoola.post.generalData = ayoola.div.getFormValues( form );
							
							//	save state
							ayoola.post.generalDataBackUp = target;
							ayoola.post.restarting.generalData = false;
						//	alert( target.elements['post_title'] );
							ayoola.post.lastCommand();
							if( e.preventDefault ){ e.preventDefault(); }
						}, 
						when: 'submit'
					}
				),
				appendedHtml: document.getElementById( 'ayoola_post_categories' ) 
								? document.getElementById( 'ayoola_post_categories' ).innerHTML
								: ''
			});
			ayoola.post.setToContainer( form );
			return false;
		}
		ayoola.post.formData += '&' + ayoola.post.generalData;
		
//		alert( ayoola.post.formData );
	//	alert( postContent )
		var uniqueNameForAjax = "ayoola_post_request";
		ayoola.xmlHttp.fetchLink( ayoola.post.url, uniqueNameForAjax, ayoola.post.formData );
	//	alert( arguments.length );
		ayoola.post.ajax = ayoola.xmlHttp.objects[uniqueNameForAjax];
		//	alert( ayoola.xmlHttp.isReady( ajax ) );	
		var ajaxCallback = function()
		{
		//	alert( ajax );
		//	alert( ajax.readyState ); 
		//	alert( ajax.status ); 
			if( ayoola.xmlHttp.isReady( ayoola.post.ajax ) )
			{ 
			//	alert( ajax.responseText ); 
			//	alert( "Template Saved." ); 
				ayoola.post.setToContainer( ayoola.post.ajax.responseText );
				
			} 
		}
		ayoola.events.add( ayoola.post.ajax, "readystatechange", ajaxCallback );
	//	document.getElementById( 'ayoola_post_categories' ).innerHTML
		
		ayoola.post.setToContainer( document.createTextNode( 'Creating Post...' ) );

	//	ayoola.post.types[ayoola.post.type].init();
	//	alert( ayoola.post.type );
	//	alert( ayoola.post.title );
		
	},
		
	//	Sets a type for the post
	setType: function( type )
	{
		if( type )
		{
			ayoola.post.type = type;
		}
		else
		{
			var form = ayoola.form.init
			({ 
				elements: 
				{
					article_type: 
					{ 
						type: 'radio', 
						style: 'display:inline-block;', 
						labelStyle: 'display:inline-block;', 
						label: ' Create a new: ', 
						value: ayoola.post.type || '', 
						callbacks: new Array
						(
							{
								callback: function( e )
								{
									var target = ayoola.events.getTarget( e );
								//	target.form.submit();
									ayoola.post.type = target.value;
									ayoola.post.restarting.type = true;
									ayoola.post.restarting.generalData = true;
/* 									{ 
										type = true,
										generalData = true
									};
 */								//	alert( target.value );
									ayoola.post.lastCommand();
								}, 
								when: 'click'
							}
						),
							
						placeholder: 'What kind of post do you want to create?', 
						select: new Array
						({ 
							value: 'article', 
							label: 'Article' 
						},
						{ 
							value: 'video', 
							label: 'Video' 
						
						},
						{ 
							value: 'photo', 
							label: 'Photo' 
						
						},
						{ 
							value: 'music', 
							label: 'Music' 
						
						},
						{ 
							value: 'download', 
							label: 'Download' 
						
						},
						{ 
							value: 'subscription', 
							label: 'Product or Service' 
						
						},
						{ 
							value: 'poll', 
							label: 'Poll' 
						
						},
						{ 
							value: 'quiz', 
							label: 'Quiz' 
						
						}), 
					},
				},
				callbacks: new Array()
			});
		}
		return form;
		
	},
		
	//	Sets a title for the post
	setTitle: function( title )
	{
		if( title )
		{
			ayoola.post.title = title;
		}
		else
		{
			//	Request for title
			var form = ayoola.form.init
			({ 
				elements: 
				{
					article_title: 
					{ 
						type: 'text', 
						label: ayoola.post.type ? ( 'Title of ' + ayoola.post.type ) : 'Title', 
						value: ayoola.post.title || '', 
						callbacks: new Array(),
						placeholder: ayoola.post.type ? ( 'Enter the title for this ' + ayoola.post.type ) : 'Choose a title for this post...', 
					},
					
					submit: 
					{ 
						type: 'submit', 
						label: null, 
						value: 'Continue', 
						callbacks: new Array(),
						placeholder: null, 
					} 
				},
				callbacks: new Array
				(
					{
						callback: function( e )
						{
						//	alert( e );
							var target = ayoola.events.getTarget( e );
							ayoola.post.title = target.elements['article_title'].value;
						//	alert( target.elements['article_title'] );
							ayoola.post.lastCommand();
							if( e.preventDefault ){ e.preventDefault(); }
						}, 
						when: 'submit'
					}
				)
			});
		//	ayoola.post.setToContainer( form );
		}
		return form;
		
	},
		
	//	Sets a view to the container
	setToContainer: function( view )
	{
		//	Header 
		var type = ayoola.post.setType();
		if( ayoola.post.type )
		{
			ayoola.div.setToContainer( ayoola.post.setType(), ayoola.post.container );
			if( ayoola.post.title )
			{
				var h = document.createElement( 'h1' );
				h.appendChild( document.createTextNode( ayoola.post.title ) );
				var a = document.createElement( 'a' );
				a.setAttribute( 'href', 'javascript:;' );
				a.setAttribute( 'class', 'badnews' );
				a.setAttribute( 'title', 'Change ' + ayoola.post.type + ' title.' );
				var changeTitle = function()
				{
					var form = ayoola.post.setTitle();
					ayoola.post.setToContainer( form );
				}
				ayoola.events.add( a, 'click', changeTitle );
				a.appendChild( document.createTextNode( ' x ' ) );
				h.appendChild( a );
				ayoola.div.setToContainer( h, ayoola.post.container, true );
			}
			ayoola.div.setToContainer( view, ayoola.post.container, true );
		}
		else
		{
			ayoola.div.setToContainer( view, ayoola.post.container );
		}
	}
}
