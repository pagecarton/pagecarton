//	Include the neccessary css, js files
//ayoola.files.loadJsObject( 'style' );

//	Class Begins
ayoola.events =
{
	event: null, // Name of the event
	eventList: { }, // Name of the event
		
	//	Add a callback to an event
	add: function( object, event, callback, checkUnique )
	{
		if( checkUnique )
		{
			var hash = ayoola.js.hashCode( callback );
			if( ayoola.events.eventList[hash] )
			{
				if( ayoola.events.eventList[hash].indexOf( object ) != -1 )
				{
					return false;
				}
			}
			else
			{
				ayoola.events.eventList[hash] = new Array();
			}
			ayoola.events.eventList[hash].push( object );  
		}
		if( ! object ){ return false; }
	//	alert( object );
		//	First remove it 
		ayoola.events.remove( object, event, callback );
		if( object.addEventListener ){ object.addEventListener( event, callback, true ); }
		else if( object.attachEvent ){ object.attachEvent( 'on' + event, callback ); }
		else{ return false; }
		return true;
	},
		
	//	Remove a callback from an event
	remove: function( object, event, callback )
	{
	//	alert( object );  
	//	alert( object.removeEventListener ); 
		
		if( ! object ){ return false; }
		if( object.removeEventListener )
		{ 
	//		alert( object.removeEventListener( event, callback, false ) );
	//		alert( object.removeEventListener( event, callback, true ) );
			object.removeEventListener( event, callback, true ); 
		}
		else if( object.detachEvent ){ object.detachEvent( 'on' + event, callback ); }
		else{ return false; }
		return true;
	},
	
	//	Returns the event object passed by the OS
	getEvent: function( e )
	{
		var e = e || window.event; //	Makes it compatible in multiple platforms
		return e;
	//	return false;
	},
	
	//	Returns the target from the event object passed by the OS
	getTarget: function( e, objectClassName )
	{
		//objectClassName = objectClassName || ayoola.dragNDrop.draggableClassName;
		var target = e.target || e.srcElement; //	Makes it compatible in multiple platforms
		if( ! objectClassName ){ return target; }
		var parentTarget = target;
		
		//	Loops through parents to find the target
		while( parentTarget )
		{
			//alert( parentTarget.className + ' ' + objectClassName );
			if( ayoola.style.hasClass( parentTarget, objectClassName ) ){ target = parentTarget; break; }
			parentTarget = parentTarget.parentNode;
		}
		return target;
	},
	
	//	Returns the mouse position from the event object passed by the OS
	getMousePosition: function( e )
	{
		var mousePosition = new Object;
		mousePosition['x'] = parseInt( e.pageX || e.clientX + document.body.scrollLeft - document.body.clientLeft );
		mousePosition['y'] = parseInt( e.pageY || e.clientY + document.body.scrollTop  - document.body.clientTop );
		
		//	returns an object
		return mousePosition;
	},
	
	//	Check if mouse is inside an object
	inObjectPosition: function( object, e )
	{		
		//alert( e );
		var mousePosition = ayoola.events.getMousePosition( e );
		var objectPosition = ayoola.events.getObjectPosition( object );
		if( 
				objectPosition.offsetLeft < mousePosition.x && objectPosition.offsetTop < mousePosition.y 
			&&  objectPosition.offsetLeft + objectPosition.offsetWidth > mousePosition.x
			&&  objectPosition.offsetTop + objectPosition.offsetHeight > mousePosition.y 
		)
		{
			return true;
		}
		return false;
	},
	
	//	Returns the browser position information
	getBrowserPosition: function()
	{	
		var browserPosition = new Object;
		browserPosition.screenTop = window.screenTop;
		browserPosition.screenLeft = window.screenLeft;
		
		//	Return the screen information
		browserPosition.availHeight = screen.availHeight;
		browserPosition.availWidth = screen.availWidth;
		browserPosition.height = browserPosition.availHeight - browserPosition.screenTop;
		browserPosition.width = browserPosition.availWidth - browserPosition.screenLeft;
	//	alert( window.screenTop );
	//	alert( screen.availHeight );
		return browserPosition;
	},
	
	//	Set the dragged element to a position
	setObjectPosition: function( x, y, object )
	{		
	//	object = object || ayoola.dragNDrop.draggedObjectDemo;
		object.style.left = x + 'px';
		object.style.top = y + 'px';
		return false;
	},
	
	//	Get the position of the an element
	getObjectPosition: function( object )
	{		
	//	object = object ? object : ayoola.dragNDrop.draggedObject;
		var result = new Object;
		//alert( ayoola.dragNDrop.draggedObject.id );
		result['offsetLeft'] = result['x'] = object.offsetLeft;
		result['offsetTop'] = result['y'] = object.offsetTop;
		result['offsetWidth'] = result['width'] = object.offsetWidth;
		result['offsetHeight'] = result['height'] = object.offsetHeight;
		return result;
	}
}
