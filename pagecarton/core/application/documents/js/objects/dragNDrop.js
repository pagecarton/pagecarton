//	Include the neccessary css, js files
//ayoola.files.loadJsObjectCss( 'dragNDrop' );
ayoola.files.loadJsObject( 'events' );
ayoola.files.loadJsObject( 'div' );
ayoola.files.loadJsObject( 'style' );

//	Class Begins
ayoola.dragNDrop =
{
	draggableClassName: ' dragBox', // Class name of draggable elements
	masterDragboxContainerClassName: ' masterDragboxContainer', // Class name of master draggable element containers
	mouseoverDragboxContainerClassName: ' mouseoverDragboxContainer', // Class name of draggable element containers
	dragboxContainerClassName: ' dragboxContainer', // Class name of draggable element containers
	draggedObjectDemoClassName: ' dragboxDemo', // Class name of the element being dragged
	draggedObjectPlaceholderClassName: ' dragboxPlaceholder', // Class name of the element being dragged
	draggedDragboxClassName: ' draggedDragbox', // Class name of the element being dragged
	mouseoverDragboxClassName: ' mouseoverDragbox', // Class name of the element being dragged
	mousedownDragboxClassName: ' mousedownDragbox', // Class name of the element being dragged
	mousemoveDragboxClassName: ' mousemoveDragbox', // Class name of the element being dragged
	dragboxViewParameterIdSuffix: '_view_parameters_id', // What we add to the dragbox id to make the view parameter
	dragboxViewOptionIdSuffix: '_view_options_id', // What we add to the dragbox id to make the view option
	draggedObjectZIndex: 10000, // Class name of the element being dragged
	initMousePosition: new Object, // Initial Mouse Position
	initDragboxPosition: new Object, // Initial Element Position
	draggables: [], // Array of Draggable Elements
	draggablesId: [], // Array of Id of Draggable Elements
	dragboxContainers: [], // Array of Dragbox Container Elements
	dragboxContainersId: [], // Array of Id of Dragbox Container Elements
	masterDragboxContainers: [], // Array of Master Dragbox Container Elements
	masterDragboxContainersId: [], // Array of Id of Master Dragbox Container Elements
	draggedObject: null, // Object Currently being dragged
	draggedObjectDemo: document.createElement( 'div' ), // Demo to show while dragging Object
	draggedObjectPlaceholder: document.createElement( 'div' ), // Demo to show while dragging Object
	
	//	Initialize the objects for automatic enablement
	init: function()
	{
	//	var auto = Array( ayoola.dragNDrop.draggablesId, ayoola.dragNDrop.dragboxContainersId, ayoola.dragNDrop.masterDragboxContainersId );
	//	alert( ayoola.dragNDrop.dragboxContainersId.length );
		for( var a = 0; a < ayoola.dragNDrop.masterDragboxContainersId.length; a++ ){ ayoola.dragNDrop.setMasterDragboxContainer( document.getElementById( ayoola.dragNDrop.masterDragboxContainersId[a] ) ); }
		for( var a = 0; a < ayoola.dragNDrop.dragboxContainersId.length; a++ ){ ayoola.dragNDrop.makeDragboxContainer( document.getElementById( ayoola.dragNDrop.dragboxContainersId[a] ) ); }
		for( var a = 0; a < ayoola.dragNDrop.draggablesId.length; a++ ){ ayoola.dragNDrop.makeDraggable( document.getElementById( ayoola.dragNDrop.draggablesId[a] ) ); }
	},
	
	//	Initialize draggable element
	makeDraggable: function()
	{
		//	Expects more than one arguments of like types
		for( var a = 0; a < arguments.length; a++ )
		{
			var element = arguments[a]; //
		//	alert( arguments[a] );
			if( typeof element == 'string' )
			{
				ayoola.dragNDrop.draggablesId.push( element );
				//	Make room for multiple elements with the same ID
				element = document.getElementById( element );
			}
			if( ! element || element.nodeName == '#text' ){	continue; }

			//	Do something about the childnodes - view parameters, options etc
			for( var b = 0; b < element.childNodes.length; b++ )
			{  var dragboxChild = element.childNodes[b];
				with( dragboxChild )
				{ 
					//	Make view parameter editable
					if( dragboxChild.id == element.id + ayoola.dragNDrop.dragboxViewParameterIdSuffix ){ ayoola.div.makeEditable( dragboxChild ); }

				}				
			}
			
		
			//if( ! element.getElementById( 'dragboxDeleteLink' ) ){ element.innerHTML += ayoola.dragNDrop.dragboxDeleteLink; }
			//	alert( element );
			element.className += ayoola.dragNDrop.draggableClassName;
			//element.innerHTML += ayoola.dragNDrop.dragboxDeleteLink;
			ayoola.events.add( element, 'mousedown', ayoola.dragNDrop.select );
			ayoola.dragNDrop.draggables.push( element );
		}
		return false;
	},
	
	//	Initialize dragbox Container
	makeDragboxContainer: function()
	{
		//	alert( arguments[0] );
		//	Expects more than one arguments of like types
		for( var i = 0; i < arguments.length; i++ )
		{
			var element = arguments[i]; //
			//alert( arguments[i] );
			if( typeof element == 'string' )
			{
				ayoola.dragNDrop.dragboxContainersId.push( element );
				element = document.getElementById( element ); //
			}
			//alert( element );
			if( ! element ){ continue; }
			element.className += ayoola.dragNDrop.dragboxContainerClassName;
			//element.onMouseover = function(){};
			
			//	Make all Childs a dragbox
			for( var j = 0; j < element.childNodes.length; j++ )
			{
				if( ! element.childNodes[j] ){ continue; }
				ayoola.dragNDrop.makeDraggable( element.childNodes[j] );
				
			}
			
			ayoola.events.add( document, 'mouseover', ayoola.dragNDrop.inspectDragboxContainers );
		//	alert( element.id );
			ayoola.dragNDrop.dragboxContainers.push( element );
		}
		return false;
	},
	
	//	Set master dragbox Container
	setMasterDragboxContainer: function()
	{
		for( var a = 0; a < arguments.length; a++ )
		{
			var element = arguments[a]; //
			if( typeof element == 'string' )
			{
				ayoola.dragNDrop.masterDragboxContainersId.push( element );
				element = document.getElementById( element ); //
			}
			if( ! element ){ continue; }
			element.className += ayoola.dragNDrop.masterDragboxContainerClassName;
			ayoola.dragNDrop.makeDragboxContainer( element );
			//	alert( element );
			ayoola.dragNDrop.masterDragboxContainers.push( element );
		}
		return false;
	},
	
	//	Checks if element is draggable 
	select: function( e )
	{
		//	If one object is being dragged, don't select another
		if( ayoola.dragNDrop.draggedObject ){ return false; }
		//	Acquire the target
		var e = ayoola.events.getEvent( e );
		var target = ayoola.events.getTarget( e );
		if( ! ayoola.dragNDrop.isDraggable( target ) ){ return false; }
		var element = ayoola.dragNDrop.draggedObject = ayoola.events.getTarget( e, ayoola.dragNDrop.draggableClassName );	//e.target || e.srcElement;
			//alert( element );
		//	Prevent text selection
		if( e.preventDefault ){ e.preventDefault(); }
		document.body.focus(); 
		document.onselectstart = function () { return false; };

			//alert( element );
		//	Element must be draggable
		//if( ! ayoola.dragNDrop.isDraggable( element ) ){ return false; }
		
		ayoola.dragNDrop.setDraggedObjectDemo( true );
					
		ayoola.style.removeClass( element, ayoola.dragNDrop.draggableClassName );
		ayoola.style.addClass( element, ayoola.dragNDrop.mousedownDragboxClassName );
		
		ayoola.events.add( document, 'mousemove', ayoola.dragNDrop.drag );
		
		//	Set Mouse Position
		ayoola.dragNDrop.initMousePosition = ayoola.events.getMousePosition( e );
			//alert( element.id );

		//	Set Element initial Positions
		ayoola.dragNDrop.initDragboxPosition = ayoola.events.getObjectPosition( element );

		return false;
	},
	
	//	Drag Element
	drag: function( e )
	{
		var element = ayoola.dragNDrop.draggedObjectPlaceholder;
	
		//	An Object must be selected, to be dragged
		if( ! element )
		{
			return false;
		}
		//element.style.display = 'none'; 
		var e = ayoola.events.getEvent( e );
		
		//	Check if we are in a dragbox Container
		var container = ayoola.dragNDrop.inDragboxContainer( e );
		if( container )
		{		
			ayoola.dragNDrop.setDraggedObjectPlaceholder( true );
			//if( ayoola.dragNDrop.isMasterDragboxContainer( container ) ) { element = element.cloneNode( true ) }
			
			//	Check if it is after or before some other dragboxes
			for( var a = 0; a < container.childNodes.length; a++ )
			{
				var dragbox = container.childNodes[a];
				//if( ! ayoola.dragNDrop.isDraggable( dragbox ) ){ continue; }
				
				if ( ayoola.events.inObjectPosition( dragbox, e ) )
				{ 
					var previousDragbox = dragbox;
				}
			}
			previousDragbox ? container.insertBefore( element, previousDragbox ) : container.appendChild( element );
			
			
		}
		else
		{
			ayoola.dragNDrop.setDraggedObjectPlaceholder( false );
		}

		//	Current mouse minus Init Mouse plus object offset
		var y = ayoola.dragNDrop.initDragboxPosition.y + ayoola.events.getMousePosition( e ).y - ayoola.dragNDrop.initMousePosition.y;
		var x = ayoola.dragNDrop.initDragboxPosition.x + ayoola.events.getMousePosition( e ).x - ayoola.dragNDrop.initMousePosition.x;
		ayoola.events.setObjectPosition( x, y, ayoola.dragNDrop.draggedObjectDemo ); 
		document.body.focus(); 
		document.onselectstart = function () { return false; };
			//alert( element.innerHTML );
		//	document.onmouseup = ayoola.dragNDrop.drop;
		ayoola.events.add( document, 'mouseup', ayoola.dragNDrop.drop );
		ayoola.style.removeClass( ayoola.dragNDrop.draggedObject, ayoola.dragNDrop.mousedownDragboxClassName );
		ayoola.style.addClass( ayoola.dragNDrop.draggedObject, ayoola.dragNDrop.mousemoveDragboxClassName );
		
		return false;
	},
	
	//	Drag Element
	drop: function( e )
	{
		ayoola.style.removeClass( ayoola.dragNDrop.draggedObject, ayoola.dragNDrop.mousemoveDragboxClassName );
		ayoola.style.addClass( ayoola.dragNDrop.draggedObject, ayoola.dragNDrop.draggableClassName );
			//alert( ayoola.dragNDrop.draggedObject.className );
		var e = ayoola.events.getEvent( e );
		
		//	Master Dragbox Containers allow for replenishing of dragged elements
		var element = ayoola.dragNDrop.draggedObject;
		if( ayoola.dragNDrop.fromMasterDragboxContainer( element ) )
		{
			element = ayoola.dragNDrop.draggedObject.cloneNode( true );
			ayoola.dragNDrop.makeDraggable( element );
		}

		//	Check if we are in a dragbox Container
		var container = ayoola.dragNDrop.inDragboxContainer( e );
	//	if( container )
		{			
			
			//if( ayoola.dragNDrop.MasterDragboxContainer( container ) ) { element = element.cloneNode( true ) }
			
			//	Check if it is after or before some other dragboxes
			for( var a = 0; a < container.childNodes.length; a++ )
			{
				var dragbox = container.childNodes[a];
				if( ! dragbox || dragbox.nodeName == '#text' ){	continue; }
				//if( ! ayoola.dragNDrop.isDraggable( dragbox ) ){ continue; }
				//alert( dragbox.innerHTML );
				if ( ayoola.events.inObjectPosition( dragbox, e ) ){ var previousDragbox = dragbox; }
				if( dragbox.innerHTML == ayoola.dragNDrop.draggedObject.innerHTML ){ var duplicate = dragbox; }
			}
			previousDragbox ? container.insertBefore( element, previousDragbox ) : container.appendChild( element );
			
			//	Do something about duplicate dragboxes? 
			if( duplicate )
			{
				//if( confirm( 'Remove the previous item of the same kind?') ){ duplicate.parentNode.removeChild( element ) };
			}
		}
		ayoola.dragNDrop.setDraggedObjectDemo( false );
		ayoola.dragNDrop.setDraggedObjectPlaceholder( false );
		ayoola.dragNDrop.draggedObject = null
		//	Cleanup
		
		//document.onmousemove = null;
		//document.onmouseup = null;
		ayoola.events.remove( document, 'mousemove', ayoola.dragNDrop.drag );
		ayoola.events.remove( document, 'mouseup', ayoola.dragNDrop.drop );
		
		return false;
	},

	//	Checks if dragged item is draggable
	isDraggable: function( object )
	{
		return ayoola.style.hasClass( object, ayoola.dragNDrop.draggableClassName );
	},

	//	Checks if a container is a master container
	isMasterDragboxContainer: function( container )
	{
		for( var a = 0; a < ayoola.dragNDrop.masterDragboxContainers.length; a++ )
		{
			if( container == ayoola.dragNDrop.masterDragboxContainers[a] ){ return true; }
		}
		return false;
	},

	//	Checks if an element came from a master container
	fromMasterDragboxContainer: function( element )
	{
		if( ! element || ! element.parentNode ){ return false; }
		return ayoola.dragNDrop.isMasterDragboxContainer( element.parentNode );
	},

	//	Checks if dragged item is draggable
	isDragboxContainer: function( object )
	{
		return ayoola.style.hasClass( object, ayoola.dragNDrop.dragboxContainerClassName );
	},
	
	//	Checks if dragged item is inDragboxContainer
	inDragboxContainer: function( e )
	{
		for( var a = 0; a < ayoola.dragNDrop.dragboxContainers.length; a++ )
		{
			var container = ayoola.dragNDrop.dragboxContainers[a];
			with( container )
			{
				if ( ayoola.events.inObjectPosition( container, e ) )
				{
					return container;
				}
			}
			
		}
		return false;
	},
	
	//	When a container is clicked
	inspectDragboxContainers: function()
	{
		for( var a = 0; a < ayoola.dragNDrop.dragboxContainers.length; a++ )
		{
			var target = ayoola.dragNDrop.dragboxContainers[a];
				//alert( target.id );
			ayoola.div.toFormElements( target, ayoola.dragNDrop.dragboxViewOptionIdSuffix );
			ayoola.div.toFormElements( target, ayoola.dragNDrop.dragboxViewParameterIdSuffix );
		}
	},
	
	//	Set the dragged element to a position
	setDraggedObjectDemo: function( flag )
	{
		if( ! ayoola.dragNDrop.draggedObject ){ return false; }

		//	Remove previous demos
		ayoola.dragNDrop.draggedObjectDemo.innerHTML = '';
		
		//	Create the demo to drag around
		var newDemo = ayoola.dragNDrop.draggedObject.cloneNode ? ayoola.dragNDrop.draggedObject.cloneNode( true ) : ayoola.dragNDrop.setDraggedObjectPlaceholder( flag )
		ayoola.dragNDrop.draggedObjectDemo.appendChild( newDemo );
		ayoola.dragNDrop.draggedObjectDemo.style.display = flag ? 'block' : 'none';
		ayoola.dragNDrop.draggedObjectDemo.className = ayoola.dragNDrop.draggedObjectDemoClassName;
		
		//	Start dragging from the same position the object is
		ayoola.dragNDrop.draggedObject.parentNode.insertBefore( ayoola.dragNDrop.draggedObjectDemo, ayoola.dragNDrop.draggedObject );
		return ayoola.dragNDrop.draggedObjectDemo;
	},
	
	//	Switches the placeholder on or off
	setDraggedObjectPlaceholder: function( flag )
	{
		if( ! ayoola.dragNDrop.draggedObject ){ return false; }

		//	Remove previous placeholders
		ayoola.dragNDrop.draggedObjectPlaceholder.innerHTML = '';
		
		//	Create the demo to drag around
		ayoola.dragNDrop.draggedObjectPlaceholder.style.display = flag ? 'block' : 'none';
		
		var draggedObjectPosition = ayoola.events.getObjectPosition( ayoola.dragNDrop.draggedObject );
		//alert( draggedObjectPosition );
		ayoola.dragNDrop.draggedObjectPlaceholder.style.height = draggedObjectPosition.height + 'px';
		//ayoola.dragNDrop.draggedObjectPlaceholder.style.width = draggedObjectPosition.width + 'px';
		ayoola.dragNDrop.draggedObjectPlaceholder.className = ayoola.dragNDrop.draggedObjectPlaceholderClassName;
		
		//	Start dragging from the same position the object is
		//ayoola.dragNDrop.draggedObject.parentNode.insertBefore( ayoola.dragNDrop.draggedObjectPlaceholder, ayoola.dragNDrop.draggedObject );
		return ayoola.dragNDrop.draggedObjectPlaceholder;
	},

	draggedElements: []
	,
	allowDrop: function (ev) 
	{
		ev.preventDefault();
	},

	dragThisElement: function (ev) 
	{
	//	alert();
		var element = ev.target;
		var index = ayoola.dragNDrop.draggedElements.indexOf( element );
		if( index == -1 ) 
		{
			// not already existing in the array, add it now
			ayoola.dragNDrop.draggedElements.push(element);
			index = ayoola.dragNDrop.draggedElements.length - 1;
		}
		ev.dataTransfer.setData("index", index );
	},

	dragMyParent: function (ev) 
	{
		//	lets drag just the title so no conflict with contenteditable
	//	alert();
		var element = ev.target.parentNode;
		var index = ayoola.dragNDrop.draggedElements.indexOf( element );
		if( index == -1 ) 
		{
			// not already existing in the array, add it now
			ayoola.dragNDrop.draggedElements.push(element);
			index = ayoola.dragNDrop.draggedElements.length - 1;
		}
		ev.dataTransfer.setData("index", index );
	},

	elementDropped: function (ev, target ) 
	{
	//	alert( ev );
	//	alert( ev.target );
		var overedElement = ev.target;
		var nextElement;
		var element = ayoola.dragNDrop.draggedElements[ev.dataTransfer.getData('index')];
		if( ev.target != target )
		{
			nextElement = overedElement;
			while( nextElement.getAttribute( 'class' ) != element.className  )
			{
		//		alert( nextElement.tagName );
		//		alert( nextElement.getAttribute( 'draggable' ) );
				if( nextElement == target )
				{
					nextElement = false;
					break;
				}
				nextElement = nextElement.parentNode;
			}
		}
		
	//	alert(  );
		if( nextElement )
		{
			target.insertBefore( element, nextElement );
		}
		else
		{
			target.appendChild(element);
		}
		ev.preventDefault();

	//	var data = ev.dataTransfer.getData("text");
	//	ev.target.appendChild(document.getElementById(data));
	},

}
