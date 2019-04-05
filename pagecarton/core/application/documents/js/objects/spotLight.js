//	Include the neccessary css, js files
//	ayoola.files.loadJsObjectCss( 'spotLight' );
//	ayoola.files.loadJsObject( 'events' );
//	ayoola.files.loadJsObject( 'style' );

	//	alert( ayoola.events.getBrowserPosition().width / 2 );
//	Class Begins
ayoola.spotLight =
{
	SpotLight: null, // The spotlight background element
	name: 'Ayoola_SpotLight', // The spotlight background element
	background: null, // The spotlight background element
	defaultHeight: '600px', // Default Height
	defaultWidth: '800px', // Default Width
	allowedParameters: { height: true, width: true, spotLight: false, changeElementId: true }, // Default Width
	parameters: {  }, // Default Width
	backgroundClassName: 'spotLightBackground', // Classname given to the background of the spotlight
	spotLightLinkClassName: 'spotLightLink', // Classname given to the background of the spotlight
	spotLightContainerClassName: 'spotLightContainer', // Classname given to the background of the spotlight
	spotLightClassName: 'spotLight', //	Classname of the spotlight
	iframeStyle: 'width: 100% ! important; border: medium none ! important; overflow: auto ! important; height: 90% ! important;', //	Classname of the spotlight
	splashScreenObject: new Object, //	Classname of the spotlight
	delete: null, //	Classname of the spotlight
	instance: new Object, //	Classname of the spotlight



	//	Initialize the object to make all enabled anchor elements showable on the spotlight
	init: function()
	{
		//ayoola.xmlHttp.setAfterStateChangeCallback( ayoola.spotLight.init );
	},

	//	Show a link in the spotlight
	play: function( e )
	{
		var target = ayoola.events.getTarget( e );
		if( ! ayoola.spotLight.isPlayable( target ) ){ return false; }
		var background = ayoola.spotLight.getBackground();
		background.innerHTML = '';

		var elementContainer = document.createElement( 'div' );
		ayoola.style.addClass( elementContainer, ayoola.spotLight.spotLightContainerClassName );
		var changeElementId = ayoola.spotLight.parameters.changeElementId;
	//	var changeElement = document.getElementById( changeElementId );

		var element = ayoola.spotLight.getSpotLight();
	//	element.setAttribute( 'align', 'right' );
		//	Check if there is a classPlayerUrl
		var rel = ayoola.div.getAnchorRel( target );
		element.src =  rel.classPlayerUrl || target.href;
	//	alert( element.src );
		element.name =  ayoola.spotLight.name;
		var elementOnloadCallback = function( ev )
		{
			var frame = element.contentDocument;
		//	alert( frame.documentElement.innerHTML );
			var stateChangeCallback = function()
			{
				var confirmation = frame.getElementById( changeElement + '_confirmed' );
				if( confirmation )
				{
				//	alert( confirmation.innerHTML );
				//	changeElement.submit();
				}
			}
			ayoola.xmlHttp.setAfterStateChangeCallback( stateChangeCallback )
		//	alert( element );
		};

		if( ! ayoola.events.add( element, 'load', elementOnloadCallback ) )
		{
			element.onload =  elementOnloadCallback;
		}
	//	var deleteButton = ayoola.div.getDelete( element, elementContainer, background );
	//	changeElement.submit();
	//	ayoola.events.add( deleteButton, 'click', function(){ ayoola.spotLight.close(); } );
	//	ayoola.events.add( deleteButton, 'click', function(){ ayoola.xmlHttp.refreshElement( changeElementId ); } );
	//	target.href = 'javascript:';
	//	var elementPosition = ayoola.spotLight.setPosition( element );
//	 	alert( ayoola.events.getBrowserPosition().width );
	// 	alert( parseInt( element.style.width ) / 2 );
	//	background.appendChild( element );
		ayoola.spotLight.popUp( element, changeElementId );
	//	ayoola.events.setObjectPosition( elementPosition.width - 20, elementPosition.height - 20, deleteButton );
//		elementContainer.appendChild( deleteButton );
//		elementContainer.appendChild( element );
	//	background.appendChild( ayoola.div.getDelete( element, background ) );
//		document.body.appendChild( background );
//		document.body.appendChild( elementContainer ); // on
		if( e.preventDefault ){ e.preventDefault(); }
		target.focus();
		return false;
	},

	//	Using this to replace the iframe spotlight
	splashScreen: function()
	{
		var elementInfo = ayoola.spotLight.popUp( "<div style=\"height:100%; background: rgba( 0, 0, 0, 0 ) url('" + ayoola.pcPathPrefix + "/loading2.gif?y') 50% 50% no-repeat;\"></div>" );

		//	remove title
		elementInfo.element.removeChild( elementInfo.element.firstChild );

		var container = elementInfo.container;
		var background = elementInfo.background;
		var closeSplashScreen = function()
			{
			//	alert( container );
				if( container && container.parentNode )
				{
					container.parentNode.removeChild( container );
					ayoola.style.removeClass( document.body, "pc_no_scroll" );
					ayoola.style.removeClass( document.body.parentNode, "pc_no_scroll" );
				}
				if( background && background.parentNode )
				{
					background.parentNode.removeChild( background );
					ayoola.style.removeClass( document.body, "pc_no_scroll" );
					ayoola.style.removeClass( document.body.parentNode, "pc_no_scroll" );
				}

			}
		if( ! ayoola.events.add( background, 'dblclick', closeSplashScreen ) )
		{
			background.ondblclick =  closeSplashScreen;
		}
		if( ! ayoola.events.add( container, 'dblclick', closeSplashScreen ) )
		{
			container.ondblclick =  closeSplashScreen;
		}
		if( ! ayoola.events.add( container.firstChild, 'dblclick', closeSplashScreen ) )
		{
			container.firstChild.ondblclick =  closeSplashScreen;
		}
//		background.onDblClick = closeSplashScreen;
///		container. = closeSplashScreen;
			ayoola.spotLight.splashScreenObject = { close: closeSplashScreen, elementInfo: elementInfo };
		return ayoola.spotLight.splashScreenObject;
	},

	//	Using this to replace the iframe spotlight
	popUp: function( htmlText, changeElementId )
	{
		var background = ayoola.spotLight.getBackground();
	//	background.innerHTML = '';

		//	Workaround to rebuild parameters for the current element
		if( ! ayoola.spotLight.isPlayable(  ) ){ null; }

	//	e
		var elementContainer = document.createElement( 'div' );
	//	alert( ayoola.spotLight.parameters.changeElementId );
	//	var changeElementId = ayoola.spotLight.parameters.changeElementId;
		var changeElement = document.getElementById( changeElementId );

		ayoola.style.addClass( elementContainer, ayoola.spotLight.spotLightContainerClassName );
	//	var element = ayoola.spotLight.getSpotLight();
		var element = ayoola.spotLight.SpotLight = document.createElement( 'div' );
		ayoola.style.addClass( element, ayoola.spotLight.spotLightClassName );
	//	element.style.cssText = 'position:absolute;padding:1em; margin:1em; background:#fff; overflow:auto; max-width:90%; max-height:90%;';
	//	element.setAttribute( 'align', 'right' );
		//	Check if there is a classPlayerUrl
	//	var barHtmlText = '<div style="width:100%;background-color:#fff;line-height:1em;cursor:move;border:1em groove #ccc;z-index:200000;color:#60F;position:fixed;top:0px;">close</div>';
	//	alert( changeElementId );
		//	onclick="this.parentNode.parentNode.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode.parentNode.parentNode ); ayoola.xmlHttp.refreshElement( \'' + changeElementId + '\' ); 			ayoola.style.removeClass( document.body, \'pc_no_scroll\' );ayoola.style.removeClass( document.body.parentNode, \'pc_no_scroll\' );"
		var deleteButtonId = 'deletButtonForSpotLight' + Math.random();
		element.innerHTML = '<div style="opacity:0.8; display:none;" title="" class="title_bar">\
		<div class="pc_container">\
		  <span  class="pc_content_title" style=\'display: inline-block;\'></span>\
		  <span class="title_button close_button ' + deleteButtonId + '" id="' + deleteButtonId + '"  href="javascript:;" class="" title="Delete this object" > x </span>\
		  <a style="display:none;" class="title_button" name="" href="javascript:;" title="Click to show or hide advanced settings" onclick="var b = this.parentNode.parentNode.parentNode.childNodes;for( var a = 0; a < b.length; a++ ){  b[a].style.display = \'\'; } this.nextElementSibling.style.display = \'\';this.style.display = \'none\';"> &square; </a>  \
		  <a class="title_button" target="_blank" href="javascript:;" title="Open this widget in a new window or tab" onclick="var b = this.parentNode.parentNode.parentNode.getElementsByTagName( \'iframe\' );for( var a = 0; a < b.length; a++ ){  this.href = b[a].contentWindow.location.href };"> &#10140; </a>  \
		  <a class="title_button" name="" href="javascript:;" title="Refresh" onclick="var b = this.parentNode.parentNode.parentNode.getElementsByTagName( \'iframe\' );for( var a = 0; a < b.length; a++ ){  b[a].contentWindow.location.reload(true); };"> &#8635; </a>  \
		   </div><div style="clear:both;"></div>  \
		  </div>';
		switch( typeof htmlText )
		{
			case 'object':
				element.appendChild( htmlText );
			break;
			case 'string':
				element.innerHTML +=  htmlText;
			break;
		}


		var cc = element.childNodes
		var iframe = false;
		var titleBar = false;
		for( cx= 0; cx < cc.length; cx++ )
		{
	//		alert( cc[cx].tagName.toLowerCase() );
		//	alert( cc[cx].className.search( /title_bar/ ) );
			if( cc[cx].tagName.toLowerCase() == 'iframe' )
			{
				iframe = cc[cx];

				//	start blank
				// no need. iframe always start blank
				//	doing it here may make loading seem slow
			//	iframe.style.display = "none";
			}
			else if( cc[cx].className.search( /title_bar/ ) >= 0 )
			{
				titleBar = cc[cx];
			}
		}

		//	Set the title
		var setTitle = function( e )
		{
			var target = ayoola.events.getTarget( e );
			target.focus();
			if( target.contentDocument.title )
			{
/*				var title = document.createElement( 'span' );
				title.className = 'pc_content_title';
				title.innerHTML = target.contentDocument.title + ' ';
*/				var x = titleBar.getElementsByClassName( 'pc_content_title' )[0];
				x.innerHTML = "";
				x.style.backgroundImage = "none";
				var sdd = document.createTextNode( target.contentDocument.title );
				x.appendChild( sdd );
			//	for( y= 0; y < x.length; y++ )
				{
				//	x[y].parentNode.removeChild( x[y] );
				}
				target.style.display = "";
				titleBar.style.display = "";
				background.style.backgroundImage = "none";
		//		titleBar.insertBefore( title, titleBar.firstChild );
			}
			else
			{
				//	close box if page cant be loaded
				elementContainer.parentNode.removeChild( elementContainer );
			}
		}
		if( iframe && titleBar )
		{
			if( ! ayoola.events.add( iframe, 'load', setTitle ) )
			{
				iframe.onload = setTitle;
			}
			var deg = function()
			{
				elementContainer.parentNode.removeChild( elementContainer );
			}
			if( ! ayoola.events.add( iframe, 'error', deg ) )
			{
				iframe.onerror = deg;
			}
			if( ! ayoola.events.add( iframe, 'abort', deg ) )
			{
				iframe.onabort = deg;
			}

		}



	//	alert( element.src );
		element.name =  ayoola.spotLight.name;
		var deleteButton = ayoola.div.getDelete( element, elementContainer, background );
	//	var deleteButton = ayoola.div.getDelete( elementContainer, background );
	//	changeElement.submit();
	//	ayoola.events.add( deleteButton, 'click', function(){ ayoola.spotLight.close(); } );
	//	var_export( changeElementId );
		var deleteIt = function()
		{
		//	alert( changeElementId );
			ayoola.style.removeClass( document.body, "pc_no_scroll" );
			ayoola.style.removeClass( document.body.parentNode, "pc_no_scroll" );
			ayoola.xmlHttp.refreshElement( changeElementId );
			ayoola.xmlHttp.refreshElement( changeElement );
			elementContainer.parentNode.removeChild( elementContainer );
		}
		ayoola.spotLight.delete = deleteIt;
		ayoola.events.add( deleteButton, 'click', deleteIt );
	//	target.href = 'javascript:';
	//	var elementPosition = ayoola.spotLight.setPosition( element );
	//	ayoola.events.add( deleteButton, 'click', function(){ ayoola.xmlHttp.refreshElement( changeElement ); } );
		var weff = function()
		{
			if( confirm( "Close the modal box?" ) )
			{
				deleteIt();
				ayoola.style.removeClass( document.body, "pc_no_scroll" );
				ayoola.style.removeClass( document.body.parentNode, "pc_no_scroll" );
			}
		}
		ayoola.events.add( background, 'dblclick', weff );
		ayoola.events.add( elementContainer, 'dblclick', weff );

	//	elementContainer.appendChild( deleteButton );
		elementContainer.appendChild( element );
		elementContainer.appendChild( background );
	//	background.appendChild( ayoola.div.getDelete( element, background ) );
	//	document.body.appendChild( background );
		ayoola.style.addClass( document.body, "pc_no_scroll" );
		ayoola.style.addClass( document.body.parentNode, "pc_no_scroll" );
		document.body.appendChild( elementContainer ); // on
	//	if( e.preventDefault ){ e.preventDefault(); }
		ayoola.spotLight.setPosition( element );
	//	alert(  );
	//	alert( element.outerWidth() );
		var deletButtonRaw = document.getElementById( deleteButtonId );
		ayoola.events.add( deletButtonRaw, 'click', deleteIt );
		ayoola.spotLight.instance = { container: elementContainer, element: element, background: background, deleteButtonElement: deletButtonRaw, deleteButtonElementId: deleteButtonId };
		return ayoola.spotLight.instance;
	},

	//	Shows link in the spotlight
	setPopUpPosition: function( element )
	{
		element.focus();
		//	Positioning

		element.style.top = '50%';
		element.style.left = '50%';
		element.style.marginLeft = '-' + ( parseInt( element.offsetWidth ) / 2 ) + 'px';
		element.style.marginTop =  '-' + ( parseInt( element.offsetHeight ) / 2 ) + 'px';
	//	alert( element.offsetWidth );
	},

	//	Shows link in the spotlight
	showLink: function( url )
	{
		//	Pops up and let's know we are loading
		var popup = ayoola.spotLight.popUp( 'Loading...' );

		//	build iframe
		var ajax = ayoola.xmlHttp.fetchLink( url );
		var changeContent = function()
		{
			if( ayoola.xmlHttp.isReady( ajax ) )
			{
				popup.innerHTML = ajax.responseText;
				ayoola.events.add( window, 'load', function(){ ayoola.spotLight.setPosition( popup ); } );

				ayoola.spotLight.setPosition( popup );
			}

		}
		ayoola.events.add( ajax, 'readystatechange', changeContent );
	},

	//	Shows link in the spotlight using Iframe
	showLinkInIFrame: function( url, changeElementId )
	{
		//	Pops up and let's know we are loading
	//	var popup = ayoola.spotLight.popUp( 'Loading...' );

		//	build html
//		var htmlText = '<iframe name="ayoola_spotlight_showlinkiniframe" style="width: 100% ! important; border: medium none ! important; overflow: auto ! important; height: 504px ! important;" role="complementary" allowtransparency="true" frameborder="0" verticalscrolling="no" horizontalscrolling="no" scrolling="no" width="100%" src="' + url + '"></iframe>';
		var htmlText = '<iframe name="ayoola_spotlight_showlinkiniframe" style="' + ayoola.spotLight.iframeStyle + '" role="complementary" allowtransparency="true" frameborder="0" width="100%" src="' + url + '"></iframe>';
		popup = ayoola.spotLight.popUp( htmlText, changeElementId );
		return popup;
	},

	//	Check if element is playable on the spotlight
	isPlayable: function( element )
	{
		element = typeof element == 'string' ? document.getElementById( element ) : element;
		ayoola.spotLight.parameters = new Object;
		if( ! element ){ return false; }
		if( ayoola.style.hasClass( element, ayoola.spotLight.spotLightLinkClassName ) ){ return true; }
		var rel = element.getAttribute( 'rel' );
		if( typeof rel == 'string' )
		{
			rel = rel.split( ';' );
			for( var a = 0; a < rel.length; a++ )
			{
				var parameterVar = rel[a].split( '=' );
			//	alert( parameterVar[0] );
			//	alert( ayoola.spotLight.allowedParameters[parameterVar[0]] );
				if( parameterVar[0].toLowerCase() == 'spotlight' ){ var validRel = true; }
				if( parameterVar[0].toLowerCase() == 'shadowbox' ){ var validRel = true; } // Compatibility
				if( ! ayoola.spotLight.allowedParameters[parameterVar[0]] ){ continue; }
				ayoola.spotLight.parameters[parameterVar[0]] = parameterVar[1];
			//	alert( ayoola.spotLight.allowedParameters[parameterVar[0]] );
			}
			if( validRel ){ return true; }
		}
		return false;
	},

	//	Returns the spotlight element
	getSpotLight: function()
	{
	//	alert( ayoola.spotLight.SpotLight );
	//	if( ! ayoola.spotLight.SpotLight || ! ayoola.spotLight.SpotLight.src )
		{
			var element = document.createElement( 'iframe' );
//			element.setAttribute( 'scrolling', 'no' );
//			element.setAttribute( 'verticalscrolling', 'no' );
	//		element.setAttribute( 'horizontalscrolling', 'no' );
			element.setAttribute( 'role', 'complementary' );
			element.setAttribute( 'width', '100%' );
			element.setAttribute( 'style', '' + ayoola.spotLight.iframeStyle + '' );
		//	ayoola.style.addClass( element, ayoola.spotLight.spotLightClassName );
			element.style.backgroundColor = "transparent";
			element.allowTransparency = "true";
			element.frameBorder = "0";
			ayoola.spotLight.SpotLight = element;
		}
//		alert( ayoola.spotLight.SpotLight );
		return ayoola.spotLight.SpotLight;
	},

	//	Returns the spotlight element
	close: function()
	{
		//	alert( ayoola.spotLight.delete );
		//	alert( parent.ayoola.spotLight.delete );
		if( ayoola.spotLight.delete )
		{
		//	alert( ayoola.spotLight.delete );
			ayoola.spotLight.delete();
		//	return true;
		}
		else if( parent.ayoola.spotLight.delete )
		{
		//	alert( ayoola.spotLight.delete );
		//	alert( parent.ayoola.spotLight.delete );
			parent.ayoola.spotLight.delete();
		//	return true;
		}
	//	alert( ayoola.spotLight.SpotLight );
		element = parent.ayoola.spotLight.background;
	//	parent.ayoola.spotLight.background.parentNode.innerHTML = '';
	//	parent.ayoola.spotLight.SpotLight.parentNode.innerHTML = '';
		if( element && element.parentNode )
		{
			element.parentNode.removeChild( element );
			ayoola.style.removeClass( document.body, "pc_no_scroll" );
			ayoola.style.removeClass( document.body.parentNode, "pc_no_scroll" );
		}
		if( parent.ayoola.spotLight.SpotLight && parent.ayoola.spotLight.SpotLight.parentNode )
		{
			element = parent.ayoola.spotLight.SpotLight.parentNode;
			if( element && element.parentNode )
			{
				element.parentNode.removeChild( element );
				ayoola.style.removeClass( document.body, "pc_no_scroll" );
				ayoola.style.removeClass( document.body.parentNode, "pc_no_scroll" );
			}
		}
		ayoola.spotLight.SpotLight = null;
		ayoola.spotLight.parameters = {  };
//		alert( ayoola.spotLight.SpotLight );
	},

	//	Sets element to the center of the window
	setPosition: function( element )
	{
		element = element || ayoola.spotLight.getSpotLight();
/* 		element.style.width =  ayoola.spotLight.parameters.width || ayoola.spotLight.defaultWidth;
		element.style.height =  ayoola.spotLight.parameters.height || ayoola.spotLight.defaultHeight;
 */
//		alert( ayoola.spotLight.parameters.width );
		if( ayoola.spotLight.parameters.width )
		{
			element.style.minWidth =  ayoola.spotLight.parameters.width || ayoola.spotLight.defaultWidth;
			element.style.minHeight =  ayoola.spotLight.parameters.height || ayoola.spotLight.defaultHeight;
		//	element.style.maxWidth =  '100%';
		//	element.style.maxHeight =  '100%';
		//	element.style.margin =  '0 auto';
			element.style.marginLeft = '-' + ( parseInt( element.style.minWidth ) / 2 ) + 'px';
			element.style.marginTop =  '-' + ( parseInt( element.style.minHeight ) / 2 ) + 'px';
		//	element.style.marginRight = '-' + ( parseInt( element.style.width ) / 16 ) + 'px';
		//	element.style.marginBottom =  '-' + ( parseInt( element.style.height ) / 16 ) + 'px';
		}
		else
		{
			element.style.width =  '100%';
			element.style.height =  '96%';
			element.style.top =  '3%';
			element.style.left =  '0%';
		//	alert( element.offsetHeight );
		}
	//	element.style.padding =  '5px';
		var elementPosition = new Object;
	//	elementPosition.width =  ( ayoola.events.getBrowserPosition().width / 2 ) - ( parseInt( element.style.width ) / 2 );
	//	elementPosition.height =  ( ayoola.events.getBrowserPosition().height / 2 ) - ( parseInt( element.style.height ) / 2 );
	//	alert( element.offsetHeight );
	//	alert( element.style['margin-top'] );
	//	alert( element.style.height );
	//	alert( parseInt( element.style.width ) );
	//	ayoola.events.setObjectPosition( elementPosition.width, elementPosition.height, element );
		return elementPosition;
	},

	//	Returns the background element
	getBackground: function()
	{
//		if( ! ayoola.spotLight.background )
		{
			var background = document.createElement( 'div' );
			background.style.cssText = "background-image:url('" + ayoola.pcPathPrefix + "/loading2.gif?y');background-position: 50% 50%; background-repeat: no-repeat;"
			background.id = ayoola.spotLight.backgroundClassName;
			ayoola.style.addClass( background, ayoola.spotLight.backgroundClassName );
			ayoola.spotLight.background = background;

		}
		return ayoola.spotLight.background;
	}
}