//	Include the neccessary css, js files
ayoola.files.loadJsObjectCss( 'spotLight' );
ayoola.files.loadJsObject( 'events' );
ayoola.files.loadJsObject( 'style' );

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
	splashScreenObject: new Object, //	Classname of the spotlight
	
	
	
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
		var deleteButton = ayoola.div.getDelete( element, elementContainer, background );
	//	changeElement.submit();
	//	ayoola.events.add( deleteButton, 'click', function(){ ayoola.spotLight.close(); } );
		ayoola.events.add( deleteButton, 'click', function(){ ayoola.xmlHttp.refreshElement( changeElementId ); } );
	//	target.href = 'javascript:'; 
		var elementPosition = ayoola.spotLight.setPosition( element );
//	 	alert( ayoola.events.getBrowserPosition().width ); 
	// 	alert( parseInt( element.style.width ) / 2 ); 
	//	background.appendChild( element );
		
	//	ayoola.events.setObjectPosition( elementPosition.width - 20, elementPosition.height - 20, deleteButton );
		elementContainer.appendChild( deleteButton );
		elementContainer.appendChild( element );
	//	background.appendChild( ayoola.div.getDelete( element, background ) );
		document.body.appendChild( background );
		document.body.appendChild( elementContainer ); // on
		if( e.preventDefault ){ e.preventDefault(); }
		target.focus(); 
		return false;
	},
	
	//	Using this to replace the iframe spotlight
	splashScreen: function()
	{		
		var elementInfo = ayoola.spotLight.popUp( "<div style=\"height:100%; background: rgba( 255, 255, 255, 0 ) url('/loading.gif') 50% 50% no-repeat;\"></div>" ); 
		var container = elementInfo.container;
		var background = elementInfo.background;
		var closeSplashScreen = function()
			{
				if( container && container.parentNode )
				{ 
					container.parentNode.removeChild( container );  
				}
				if( background && background.parentNode )
				{ 
					background.parentNode.removeChild( background );  
				}
			
			}
			ayoola.spotLight.splashScreenObject = { close: closeSplashScreen };
		return ayoola.spotLight.splashScreenObject;
	},
	
	//	Using this to replace the iframe spotlight
	popUp: function( htmlText )
	{		
		var background = ayoola.spotLight.getBackground();
		background.innerHTML = '';
		
		//	Workaround to rebuild parameters for the current element
		if( ! ayoola.spotLight.isPlayable(  ) ){ null; }
		
	//	e
		var elementContainer = document.createElement( 'div' );
		var changeElementId = ayoola.spotLight.parameters.changeElementId;
		var changeElement = document.getElementById( changeElementId );

		ayoola.style.addClass( elementContainer, ayoola.spotLight.spotLightContainerClassName );
	//	var element = ayoola.spotLight.getSpotLight();
		var element = ayoola.spotLight.SpotLight = document.createElement( 'div' ); 
		ayoola.style.addClass( element, ayoola.spotLight.spotLightClassName );
	//	element.style.cssText = 'position:absolute;padding:1em; margin:1em; background:#fff; overflow:auto; max-width:90%; max-height:90%;';
	//	element.setAttribute( 'align', 'right' ); 
		//	Check if there is a classPlayerUrl
	//	var barHtmlText = '<div style="width:100%;background-color:#fff;line-height:1em;cursor:move;border:1em groove #ccc;z-index:200000;color:#60F;position:fixed;top:0px;">close</div>';
		switch( typeof htmlText )
		{
			case 'object': 
				element.appendChild( htmlText );
			break;
			case 'string': 
				element.innerHTML =  htmlText; 
			break;
		}
		
	//	alert( element.src );
		element.name =  ayoola.spotLight.name;
		var deleteButton = ayoola.div.getDelete( element, elementContainer, background );
	//	var deleteButton = ayoola.div.getDelete( elementContainer, background );
	//	changeElement.submit();
	//	ayoola.events.add( deleteButton, 'click', function(){ ayoola.spotLight.close(); } );
		ayoola.events.add( deleteButton, 'click', function(){ ayoola.xmlHttp.refreshElement( changeElementId ); } );
	//	target.href = 'javascript:'; 
	//	var elementPosition = ayoola.spotLight.setPosition( element );
		ayoola.events.add( deleteButton, 'click', function(){ ayoola.xmlHttp.refreshElement( changeElement ); } );
		
		elementContainer.appendChild( deleteButton );
		elementContainer.appendChild( element );
	//	background.appendChild( ayoola.div.getDelete( element, background ) );
		document.body.appendChild( background );
		document.body.appendChild( elementContainer ); // on
	//	if( e.preventDefault ){ e.preventDefault(); }
		ayoola.spotLight.setPosition( element ); 
	//	alert(  );
	//	alert( element.outerWidth() );
		return { container: elementContainer, element: element, background: background };
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
	showLinkInIFrame: function( url )
	{
		//	Pops up and let's know we are loading
	//	var popup = ayoola.spotLight.popUp( 'Loading...' );
		
		//	build html
//		var htmlText = '<iframe name="ayoola_spotlight_showlinkiniframe" style="width: 100% ! important; border: medium none ! important; overflow: auto ! important; height: 504px ! important;" role="complementary" allowtransparency="true" frameborder="0" verticalscrolling="no" horizontalscrolling="no" scrolling="no" width="100%" src="' + url + '"></iframe>';
		var htmlText = '<iframe name="ayoola_spotlight_showlinkiniframe" style="width: 100% ! important; border: medium none ! important; overflow: auto ! important; height: 100% ! important;" role="complementary" allowtransparency="true" frameborder="0" width="100%" src="' + url + '"></iframe>';
		popup = ayoola.spotLight.popUp( htmlText );
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
		//	element.setAttribute( 'role', 'complementary' ); 
			ayoola.style.addClass( element, ayoola.spotLight.spotLightClassName );
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
	//	alert( ayoola.spotLight.SpotLight );
		element = parent.ayoola.spotLight.background;
	//	parent.ayoola.spotLight.background.parentNode.innerHTML = '';
	//	parent.ayoola.spotLight.SpotLight.parentNode.innerHTML = '';
		if( element && element.parentNode )
		{ 
			element.parentNode.removeChild( element );
		}
		if( parent.ayoola.spotLight.SpotLight && parent.ayoola.spotLight.SpotLight.parentNode )
		{ 
			element = parent.ayoola.spotLight.SpotLight.parentNode;
			if( element && element.parentNode )
			{ 
				element.parentNode.removeChild( element );
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
			element.style.width =  '90%'; 
			element.style.height =  '90%'; 
			element.style.top =  '5%'; 
			element.style.left =  '5%'; 
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
			background.id = ayoola.spotLight.backgroundClassName;
			ayoola.style.addClass( background, ayoola.spotLight.backgroundClassName );
			ayoola.spotLight.background = background;

		}
		return ayoola.spotLight.background;
	}
}