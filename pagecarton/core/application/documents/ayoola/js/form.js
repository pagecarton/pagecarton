//	Include the neccessary css, js files
//ayoola.files.loadJsObjectCss( 'div' );
//ayoola.files.loadJsObject( 'events' );

//	Class Begins
ayoola.form =
{
	elements: {  }, // Elements in the form
	fieldsets: {  }, // Fieldsets in the form
	callbacks: new Array(), // Callbacks for form
	node: null, // Form Node
	elementValueChangeCallbacks: { }, // Callbacks after value of an element change { elementName: Array( callback1, callback2 ) }
	counter: {}, // Form Node
	
	// Turn an input text to a multiple input for the same variable 
	cloneElements: function( elementObject  )
	{
		element = typeof elementObject.element == 'string' ? document.getElementById(  elementObject.element ) :  elementObject.element;
		if( ! element ){ return false; }
		//	Type must be properly set
	//	if( ! element.type ){ return false; }
	//	var target = ayoola.events.getTarget( e );
		var clone = element.cloneNode( true ); 
		var id = 'elements_' + clone.id;
		
		ayoola.form.counter[id] = ayoola.form.counter[id] ? ayoola.form.counter[id] : 1;
		clone.id += '_' + ++ayoola.form.counter[id];
		clone.value = '';
	//	alert( element.nodeName );
	//	alert( element.nodeType );
		var label = document.createTextNode( '' ); //	This is to make the option always available
		if( clone.nodeName.toLowerCase() == 'fieldset' )
		{
			for( var a = 0; a < clone.childNodes.length; a++ )
			{
			//	alert( element.childNodes[a].value );
				if( clone.childNodes[a].value ){ clone.childNodes[a].value = '' };
				if( clone.childNodes[a].nodeName.toLowerCase() == 'legend' )
				{
					clone.childNodes[a].innerHTML = '';
					if( elementObject.elementOptions )
					{ 
						var eachOption = elementObject.elementOptions.shift();
						if( eachOption.label )
						{ 
							clone.childNodes[a].innerHTML = eachOption.label + ' ' + ayoola.form.counter[id];
						}
					}
				}
			}
		}
		else//	var addLabel = function( readyClone )
		{
			if( elementObject.elementOptions )
			{ 
				var eachOption = elementObject.elementOptions.shift();
				if( eachOption.label )
				{ 
					var label = document.createElement( 'label' );
					label.setAttribute( 'for', clone.id );
					label.appendChild( document.createTextNode( eachOption.label + ' ' + ayoola.form.counter[id] ) );
				}
			}
		}
		if( ! elementObject.triggerElement )
		{
		//	elementObject.triggerElement.className += ' goodnews';
			if( element.nextSibling )
			{
				clone = element.parentNode.insertBefore( clone, element.nextSibling );
				clone.parentNode.insertBefore( label, clone );
			}
			else
			{
				clone = element.parentNode.appendChild( clone );
				clone.parentNode.insertBefore( label, clone );
			}
		}
		else
		{
			clone = elementObject.triggerElement.parentNode.insertBefore( clone, elementObject.triggerElement );
			clone.parentNode.insertBefore( label, clone );
		}
/* 		//	Create Link to delete element
		var a = document.createElement( 'a' );
		a.setAttribute( 'href', 'javascript:' );
		a.setAttribute( 'title', 'Delete Option' );
		a.innerHTML = ' - ';
 */		//	Create Link to delete element
		var a = document.createElement( 'input' );
		a.setAttribute( 'type', 'button' );
		a.setAttribute( 'value', ' - ' );
		a.setAttribute( 'class', ' badnews ' );
		a.setAttribute( 'title', 'Delete Option' );
		var removeClone = function( e )
		{ 
			clone.parentNode.removeChild( clone ); 
			label.parentNode.removeChild( label ); 
			
			//	remove link 
			var target = ayoola.events.getTarget( e );
			target.parentNode.removeChild( target ); 
			
		}
		ayoola.events.add( a, 'click', removeClone );
		a = clone.parentNode.insertBefore( a, clone );
		
	}, 
				
	//	Sets the post type to a new value
	init: function( formObject )
	{
		var form = form || document.createElement( 'form' );
		var elements = formObject.elements || ayoola.form.elements;
		var fieldsets = formObject.fieldsets || {  };
		var callbacks = formObject.callbacks || ayoola.form.callbacks;
		var container = document.createElement( 'span' );
		for( var a = 0; a < callbacks.length; a++ )
		{
		//	alert( ayoola.form.callbacks[a].when );
		//	alert( ayoola.form.callbacks[a].callback );
			ayoola.events.add( form, callbacks[a].when, callbacks[a].callback );
		}
		form.setAttribute( 'data-not-playable', 'not-playable' );
		formObject.name ? form.setAttribute( 'name', formObject.name ) : null;
		formObject.id ? form.setAttribute( 'id', formObject.id ) : null;
		
		//	Sets the elements into a form object
		var sendToForm = function( element, fieldset )
		{
			if( ! fieldset )
			{
				form.appendChild( element );	
				return;
			}
			if( ! fieldsets[fieldset] ){ fieldsets[fieldset] = {}; }
			if( ! fieldsets[fieldset].element )
			{
				fieldsets[fieldset].element = document.createElement( 'fieldset' );
				fieldsets[fieldset].element.setAttribute( 'id', fieldset );
				fieldsets[fieldset].element.setAttribute( 'style', fieldsets[fieldset].style || '' );
				var legend = document.createElement( 'legend' );
				legend.innerHTML = fieldsets[fieldset].legend || '';
			//	legend.setAttribute( 'for', key );
			//	legend.appendChild( document.createTextNode( fieldsets[fieldset].legend || '' ) );
				
				fieldsets[fieldset].element.appendChild( legend );
				if( fieldsets[fieldset].prependedHtml )
				{
					var span = document.createElement( 'span' );
			//		label.setAttribute( 'for', key );
					span.innerHTML = fieldsets[fieldset].prependedHtml;
					form.appendChild( span );
				}
				form.appendChild( fieldsets[fieldset].element );
				if( fieldsets[fieldset].appendedHtml )
				{
					var span = document.createElement( 'span' );
			//		label.setAttribute( 'for', key );
					span.innerHTML = fieldsets[fieldset].appendedHtml;
					form.appendChild( span );
				}
			}
			fieldsets[fieldset].element.appendChild( element );

		}
		if( formObject.appendedHtml )
		{
			var span = document.createElement( 'span' );
	//		label.setAttribute( 'for', key );
			span.innerHTML = formObject.appendedHtml;
			sendToForm( span  );
		//	form.appendChild( span );
		}

		for( var key in elements )
		{
			var innerObject = elements[key];
		//	alert( innerObject );
			if( ! innerObject || ! innerObject.type ){ continue; }
			
			//	Label
			switch( innerObject.type )
			{
				case 'submit':
				case 'button':
				break;
				default:
					var label = document.createElement( 'label' );
					label.setAttribute( 'for', key );
				//	label.appendChild( document.createTextNode( innerObject.label || key ) );
					label.innerHTML = innerObject.label || key;
					sendToForm( label, innerObject.fieldset || '' );
				//	form.appendChild( label );
				break;
			}
			switch( innerObject.type.toLowerCase() )
			{				
				case 'textarea':
					var element = document.createElement( 'textarea' );
					element.setAttribute( 'placeholder', innerObject.placeholder || '' );
					element.setAttribute( 'id', innerObject.id || '' );
					element.setAttribute( 'style', innerObject.style || '' );
					element.setAttribute( 'name', innerObject.name || key );
					elements[key].element = element;
				//	form.appendChild( element );
				break;
				case 'html':
					var element = document.createElement( 'span' );
					element.innerHTML = innerObject.value;
					elements[key].element = element;
				//	form.appendChild( element );
				break;
				case 'select':
					var element = document.createElement( 'select' );
					element.setAttribute( 'name', innerObject.name || key );
					element.setAttribute( 'style', innerObject.style || '' );
					element.setAttribute( 'id', innerObject.id || '' );
					elements[key].element = element;
					for( var a = 0; a < innerObject.select.length; a++ )
					{
						var option = document.createElement( 'option' );
						if( innerObject.value == innerObject.select[a].value )
						{
							option.setAttribute( 'selected', 'selected' );
						}
						option.setAttribute( 'value', innerObject.select[a].value );
						option.appendChild( document.createTextNode( innerObject.select[a].label ) );
						element.appendChild( option );
					}
				break;
				case 'checkbox':
				case 'radio':
					for( var a = 0; a < innerObject.select.length; a++ )
					{
						var element = document.createElement( 'input' );
						element.setAttribute( 'type', innerObject.type );
						if( innerObject.value == innerObject.select[a].value )
						{
							element.setAttribute( 'checked', 'checked' );
						}
						element.setAttribute( 'name', innerObject.name || key );
						element.setAttribute( 'style', innerObject.style || '' );
						
						//	Making form name part of multiple input field id fix the bug where clicking the label triggers more than one fields
						element.id = element.name + '_' + key + formObject.name + '_' + a;
					//	element.setAttribute( 'id', key + '_' + a );
						element.setAttribute( 'value', innerObject.select[a].value );
					//	alert( innerObject.select[a].className );
						element.setAttribute( 'class', innerObject.select[a].className || '' );
						innerObject.callbacks = innerObject.callbacks || new Array();
						
						//	Insert callbacks
						
						for( var b = 0; b < innerObject.callbacks.length; b++ )
						{
						//	alert( element );
						//	alert( innerObject.callbacks[b].when );
						//	alert( innerObject.callbacks[b].callback );
							ayoola.events.add( element, innerObject.callbacks[b].when, innerObject.callbacks[b].callback );
						}
						elements[key].element = element;
					//	form.appendChild( element );
					
						//	elementCover allows inline-block to work on radio and checkbox
						var elementCover = document.createElement( 'span' );
						elementCover.setAttribute( 'style', innerObject.select[a].elementCoverStyle );
						elementCover.setAttribute( 'class', innerObject.select[a].elementCoverClass );
						elementCover.setAttribute( 'name', innerObject.select[a].elementCoverName );
						elementCover.setAttribute( 'onClick', innerObject.select[a].elementCoverOnClick );
						elementCover.appendChild( element );
					//	sendToForm( element, innerObject.fieldset || '' );
						var label = document.createElement( 'label' );
						label.innerHTML = innerObject.select[a].label;
						label.setAttribute( 'for', element.id );
						label.setAttribute( 'name', element.id + '_label' );
						label.setAttribute( 'style', 'margin-left:0.5em;' + innerObject.labelStyle );
						label.setAttribute( 'class', innerObject.select[a].className || '' );
					//	label.onClick = function(){ element.checked = true; }
				//		ayoola.events.add( label, 'click', function(){ element.checked = true; } );
				//		label.appendChild( document.createTextNode( innerObject.select[a].label ) );
						elementCover.appendChild( label );
						sendToForm( elementCover, innerObject.fieldset || '' );
					//	form.appendChild( label );
					}
					//	Destroy the last element because radio produces multiple input tags
					element = document.createTextNode( '' );
				break;
				case 'submit':
				case 'button':
					//	Switch submit button off by turning value to null
					if( innerObject.value == null || innerObject.hide )
					{
						break;
					}
				
				default:
					var element = document.createElement( 'input' );
					innerObject.type = innerObject.type || 'text';
					element.setAttribute( 'type', innerObject.type || '' );
					element.setAttribute( 'value', innerObject.value || '' );
					element.setAttribute( 'id', innerObject.id || '' );
					element.setAttribute( 'style', innerObject.style || '' );
					element.setAttribute( 'placeholder', innerObject.placeholder || '' );
					element.setAttribute( 'name', innerObject.name || key );
					elements[key].element = element;
				//	form.appendChild( element );
				break;
			
			}
			//	General callback register
			if( innerObject.callbacks )
			{
				for( var b = 0; b < innerObject.callbacks.length; b++ )
				{
					ayoola.events.add( element, innerObject.callbacks[b].when, innerObject.callbacks[b].callback );
				}
			}
			sendToForm( element, innerObject.fieldset || '' );
			if( innerObject.appendedHtml )
			{
				var span = document.createElement( 'span' );
		//		label.setAttribute( 'for', key );
				span.innerHTML = innerObject.appendedHtml;
				sendToForm( span, innerObject.fieldset || '' );
			//	form.appendChild( span );
			}
			
		}
	/* 	var submit = document.createElement( 'input' );
		submit.setAttribute( 'type', 'button' );
		submit.setAttribute( 'value', 'submit' );
		form.appendChild( submit ); */
		container.appendChild( form );
	//	alert( container.innerHTML );
		return form;
	}
}
