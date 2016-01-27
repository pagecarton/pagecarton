//	Class Begins
ayoola.style =
{
	name: null, // Name of the style
		
	//	Add a style to an element
	addClass: function( element, styleClassName )
	{
		if( ! element || undefined == element.className ){ return false; }
		if( ayoola.style.hasClass( element, styleClassName ) ){ return true; }
		return element.className += ' ' + styleClassName;
				//alert( element.className );
	},
		
	//	Remove a style from an element
	removeClass: function( element, styleClassName )
	{
		if( ! element || ! element.className ){ return false; }
	//	alert( element );  
	//	alert( 'ssss' );  
		element.className = element.className.replace( styleClassName, '' );
	//	alert( 'ssss' );  
	//	alert( element.className );
		
	},
		
	//	Changes Class depending on the event
	alternateClassPerEvent: function( element, styleClassName, event )
	{
		if( ! element || ! element.className ){ return false; }
		callback = function(){ ayoola.style.addClass( element, styleClassName ); }
		ayoola.events.add( element, event, callback );
	},
		
	//	Checks if element has class of name "styleClassName"
	hasClass: function( element, styleClassName )
	{
		//alert( element.className.search( /styleClassName/ ) );
		if( ! element || ! element.className ){ return false; }
		if( element.className.search( styleClassName ) == -1 ){ return false; }
		return true;
	}
}
