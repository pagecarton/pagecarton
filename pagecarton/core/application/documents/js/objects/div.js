//	Include the neccessary css, js files
//ayoola.files.loadJsObjectCss( 'div' );
//ayoola.files.loadJsObject( 'events' ); 

//	Class Begins
ayoola.div =
{
	editableDivElement: null, // The div being edited  
	textAreaForEditableDivElement: document.createElement( 'Textarea' ), // The div being edited
	editableDivWYSIWYGElements: new Object, // List of Object
	wysiwygEditor: null, // List of Object
	editableFormElementContainer: document.createElement( 'div' ), // The Textarea used in edited
	lastContentAttribute: 'lastContent', // Title to show in the div
	divTitle: 'Click here to edit content', // Title to show in the div
	editableDivClassName: 'editableDiv', // Class of editable div
	editableDivElements: [], // Storage of available editable div elements
		
	//	Edits a div editableDivElement as if they were a form field
	edit: function()
	{
		//	The function is usually invoked by mouse over. We need to reset it here.
		ayoola.events.remove( this, 'mouseover', ayoola.div.edit );
		ayoola.events.add( this, 'mousedown', ayoola.div.divToTextArea );
		
		ayoola.style.addClass( this, ayoola.div.editableDivClassName );
		if( ! this.id ){ return false; }
		
		//	Record the default content for safe keep
		this.setAttribute( ayoola.div.lastContentAttribute, this.innerHTML );
		this.setAttribute( 'title', ayoola.div.divTitle );
	},
		
	//	Picks an element out of other element
	selectElement: function( elementObject, selectMultiple )
	{
	//	alert( url );
		switch( typeof elementObject.tagName )
		{
			//	Compatibility
			case 'string':
				elementObject = { element: elementObject, selectMultiple: selectMultiple }
/* 				elementObject.element = elementObject;
				elementObject.selectMultiple = selectMultiple;
 */			break;
			case 'undefined':
			default:
			
			break;
		}
	//	alert( elementObject.element );
	//	alert( elementObject.element.name );
	//	alert( elementObject.element.getAttribute( 'name' ) );
		var element = elementObject.element;
/* 		var selectElement = function()
		{ 
			ayoola.div.selectElement( element, selectMultiple ); 
		}
 */		
		//	Switch me to normalnews if the user clicks me again
		var switchMeOff = function( target )
		{ 
			ayoola.style.removeClass( target, 'selectednews' ); 
			ayoola.style.addClass( target, 'normalnews' ); 
		//	element.className = '';
			//	remove this attribute first
		//	ayoola.events.remove( target, 'click', switchMeOff );
		//	ayoola.events.add( element, 'click', selectElement );
		}
 
 		//	Switch me to normalnews if the user clicks me again
		if( ayoola.style.hasClass( element, 'selectednews' ) && ! elementObject.disableUnSelect )
		{ 
			switchMeOff( element );
			return false;
		}

		
		var a = document.getElementsByName( elementObject.name || element.name || element.getAttribute( 'name' ) ); 
	//	alert( element.name );
	//	alert( elementObject.name );
	//	alert( a );
	//	alert( a.length );
	//	alert( elementObject.name );
	//	alert( elementObject.selectMultiple );
	//	if( ! a ){ return false; } 
		if( ! elementObject.selectMultiple )
		{
			//	Turn everything else off
			for( var b = 0; b < a.length; b++ )
			{
				ayoola.style.removeClass( a[b], 'selectednews' );
			//	alert( a[b].tagName );
				ayoola.style.addClass( a[b], 'normalnews' );
			//	b.className = ' normalnews';
			}
		}
		//	if I was selected, before, it could be I should be switched of
		//	Turn me on
		ayoola.style.removeClass( element, 'normalnews' );
		ayoola.style.addClass( element, 'selectednews' );
	//	element.className = ' selectednews ';
	//	ayoola.events.add( element, 'click', switchMeOff );
	//	ayoola.events.remove( element, 'click', selectElement );
	//	ayoola.events.add( element, 'click', c );

	},
		
	//	Turn the text area to div editableDivElement
	textAreaToDiv: function()
	{
		//	We disallow empty content
		var formValue = this.value;
		formValue = formValue.replace( /\s/gi, ''  );
		this.value = formValue ? this.value : this.getAttribute( ayoola.div.lastContentAttribute );
		var editableDivElement = document.createElement( 'div' );
		ayoola.div.setAttributes( editableDivElement, ayoola.div.getAttributes( this ) );
/* 		if( ayoola.div.wysiwygEditor )
		{
			ayoola.div.wysiwygEditor.destroy();
			ayoola.div.wysiwygEditor = null;
		}
 */
		//alert( editableDivElement );
		editableDivElement.innerHTML = this.value;
		editableDivElement[ayoola.div.lastContentAttribute] = this.lastContentAttribute;
		editableDivElement.title =  ayoola.div.divTitle;
		
		this.parentNode.insertBefore( editableDivElement, this );
		this.parentNode.removeChild( this );

		ayoola.events.add( editableDivElement, 'mousedown', ayoola.div.divToTextArea ); 
		return true;
	},
		
	//	Turn the text area to div editableDivElement
	divToTextArea: function()
	{
			//alert( this.className );
		var editableFormElement = ayoola.div.textAreaForEditableDivElement;
		editableFormElement.attributes = undefined;
		ayoola.div.setAttributes( editableFormElement, ayoola.div.getAttributes( this ) );
		
		editableFormElement.title = 'Make your changes now.'; 
	//	if( editableFormElement.id && ayoola.div.editableDivWYSIWYGElements[editableFormElement.id] )
/* 		if( CKEDITOR && ! ayoola.div.wysiwygEditor )
		{
			ayoola.events.add( editableFormElement, 'dblclick', function(){ ayoola.div.wysiwygEditor = CKEDITOR.replace( editableFormElement ); } );
			editableFormElement.title += 'Double click to Switch to advanced HTML mode'; 
		}
 */	//	editableFormElement.style.minWidth = '90%';
		editableFormElement.value = this.innerHTML || this.getAttribute( ayoola.div.lastContentAttribute );
		editableFormElement[ayoola.div.lastContentAttribute] = this.lastContentAttribute;
		
		//	Add neccessary event handlers
		ayoola.events.remove( this, 'mousedown', ayoola.div.divToTextArea );
		ayoola.events.add( editableFormElement, 'mouseout', ayoola.div.textAreaToDiv );

		this.parentNode.insertBefore( editableFormElement, this );
		this.parentNode.removeChild( this );
	//	ayoola.div.editableDivWYSIWYGElements[element.id] 
	//	ayoola.events.add( editableFormElement, 'dblclick', function(){ CKEDITOR.replace( "" ); } );
		return true;
	},
		
	//	Makes a div editable. This is the manual setting to edit method
	makeEditable: function()
	{
		//	Expects more than one arguments of like types
		for( var i = 0; i < arguments.length; i++ )
		{
			var element = arguments[i]; //
			//alert( arguments[i] );
			if( typeof arguments[i] == 'string' )
			{
				element = document.getElementById( arguments[i] ); //
			}
			//alert( element );
			if( ! element )
			{
				continue;
			}
			
			ayoola.style.addClass( element, ayoola.div.editableDivClassName );
			ayoola.events.add( element, 'mouseover', ayoola.div.edit );
		//	alert( element.id );
		}
	},
		
	//	Makes a div editable. This is the manual setting to edit method
	makeEditableWYSIWYG: function()
	{
		//	Expects more than one arguments of like types
		for( var i = 0; i < arguments.length; i++ )
		{
			var element = arguments[i]; //
			//alert( arguments[i] );
			if( typeof arguments[i] == 'string' )
			{
				element = document.getElementById( arguments[i] ); //
			}
			//alert( element );
			if( ! element ){ continue; }
			ayoola.div.makeEditable( element );
			ayoola.div.editableDivWYSIWYGElements[element.id] = element;
			
		}

	},
		
	//	Make Div tags look like formfields, ready for POST or GET
	toFormElements: function( element, suffix )
	{
		//alert( suffix );
		suffix = suffix || '';
		element = typeof element == 'string' ? document.getElementById( element ) : element;
		if( ! element ){ return false; }
		//alert( element );
		
		//	Check if the element exists
		var formElementName = element.id + suffix;
		var formElement = document.getElementById( formElementName );
		if( ! formElement ){ return false; }
		//alert( formElement.value );
		formElement.value = '';
		for( var a = 0; a < element.childNodes.length; a++ )
		{
			var child = element.childNodes[a];
			//alert( child );
			if( ! child.id ){ continue; }
			
			//	Go deeper to find required Element
			for( var b = 0; b < child.childNodes.length; b++ )
			{ 	
				var requiredElement = child.childNodes[b];
				//alert( requiredElement.id );
				if( ! ( requiredElement.id ) || requiredElement.id != child.id + suffix ){ continue; }

				//alert( requiredElement.innerHTML );
				var value = requiredElement['value'] || requiredElement.innerHTML; 
				//alert( value );
				formElement.value += child.id + '=>' + value + '::';
			}
			//alert( value );
		}
		//alert( formElement.value );
	},
				
	//	Rip the attributes present in a dom element
	getAttributes: function( element )
	{
		if( typeof element == 'string' )
		{
			element = document.getElementById( element ); //
		}
		//alert( element );
		if( ! element ){ return false; }
		var elementAttributes = new Object;
		if( ! element.attributes ){ return elementAttributes; }
		for ( var a = 0; a < element.attributes.length; a++ )
		{
	//		alert( element.attributes.item( a ).nodeValue );
	//		alert( element.attributes.item( a ).nodeName );
			var eachAttribute = element.attributes.item( a )
			var value = eachAttribute.value ? eachAttribute.value : eachAttribute.nodeValue;
			var name = eachAttribute.nodeName;
			if( value )
			{
			//	alert( value );
				elementAttributes[name] = value;
			}
		}	
		return elementAttributes;
	},
		
	//	sets the attributes
	setAttributes: function( element, elementAttributes )
	{
		if( typeof element == 'string' )
		{
			element = document.getElementById( element ); //
		}
		//alert( element );
		if( ! element ){ return false; }
		for( var key in elementAttributes )
		{
		//	alert( elementAttributes[key] );
			if ( elementAttributes.hasOwnProperty( key ) ) 
			{
				element.setAttribute( key, elementAttributes[key]);
			}
		}
		return true;
	},
		
	//	Counts elements and set innerhtml to the number of each
	refreshVisibleCounter: function( element )
	{
		if( ! element ){ return false; }
		if( typeof element == 'string' )
		{
			element = document.getElementsByName( element ); //
		}
		if( ! element ){ return false; }
		var total = document.getElementsByName( element[0].getAttribute( 'name' ) + '_total' );
	//	alert( element[0].getAttribute( 'name' ) + '_total' );
	//	alert( total.length );
		for( var a = 0; a < element.length; a++ )
		{
			element[a].innerHTML = a + 1;
		}
		for( var a = 0; a < total.length; a++ )
		{
			total[a].innerHTML = element.length;
		}
		return true;
	},
		
	//	Remove elements from the document
	getDelete: function()
	{
		var link = document.createElement( 'a' );
		var toDelete = arguments;
		link.href = 'javascript:;';
		link.className = ' badnews boxednews centerednews ';
		link.title = 'Close';
		link.style.cssText = 'text-align: right;';
		link.innerHTML = ' x ';
		//alert( link );
		
		ayoola.events.add
		( 
			link,
			'click',
			function()
			{
				for( var a = 0; a < toDelete.length; a++ )
				{ 
				//	alert( toDelete[a] );
					if( toDelete[a] && toDelete[a].parentNode )
					{
						toDelete[a].parentNode.removeChild( toDelete[a] );  
					}
				}
			}
		);
		return link;
	},
		
	//	Returns the rel of an anchor
	getAnchorRel: function( anchor )
	{
		var rel = anchor.getAttribute( 'rel' );
		var parameters = new Object;
		if( typeof rel == 'string' )
		{
			rel = rel.split( ';' );
	//		alert( rel );
			//	Convert parameter1=var1;parameter2=var2; to { parameter1: var1, parameter2: var2 } 
			for( var a = 0; a < rel.length; a++ )
			{
				rel[a] = rel[a].split( '=' );
				parameters[rel[a][0]] = rel[a][1];
			//	alert( rel[a][0] + rel[a][1] );
			}
		}
		return parameters;
	},
		
	//	Returns the great grand parents 
	getParent: function( elementObj, counter )
	{
		switch( typeof elementObj.element )
		{
			//	Compatibility
			case 'string':
			case 'object':
			
			break;
			case 'undefined':
			default:
				elementObj = { element: elementObj, counter: counter }
			break;
		}
		var parentElement = elementObj.element;
		if( typeof parentElement == 'string' ){ parentElement = document.getElementById( parentElement ); } 
		
		
		elementObj.counter = elementObj.counter || 2;
		elementObj.counter++;
		for( var a = 0; a < elementObj.counter; a++ )
		{
		//	alert( parentElement );        
			
			//	We can specify the name to look for
			if( parentElement.getAttribute 
				&& parentElement.getAttribute( 'name' ) 
				&& elementObj.name == 
				parentElement.getAttribute( 'name' ) )
			{
				break;  
			}
			
			//	We can specify the "parent limit"
			if( ! parentElement.parentNode )
			{
				break;
			}
			
			parentElement = parentElement.parentNode;
		}
		return parentElement;
	},

	getElementStyle: function( element ) 
	{
		element.currentStyle ? element.currentStyle.display : getComputedStyle( element, null ).display
	},

	getParentWithClass: function( element, className ) 
	{
		while( ( element = element.parentElement ) && ! element.classList.contains( className ) );
		return element;
	},	

	getParameterOptions: function( x, numberedSectionName )
	{
//		alert( numberedSectionName );
		numberedSectionName = numberedSectionName ? numberedSectionName : '';
		var p = "";
		var q = Array();
		for( var c = 0; c < x.childNodes.length; c++ ) 
		{
			var parameterOrOption = x.childNodes[c];
		//	alert( parameterOrOption.outerHTML );
			if( ! parameterOrOption || parameterOrOption.nodeName == "#text" ){ continue; }
			if( ! parameterOrOption.dataset || ! parameterOrOption.dataset.parameter_name )
			{ 
				continue; 
			}
			if( parameterOrOption.dataset.parameter_name == "parent"  )
			{
				//	if we are in exterior, just jump to interior and skip the wrapper
				if( parameterOrOption.className.search( /object_exterior/ ) >= 0 )
				{
					parameterOrOption = parameterOrOption.getElementsByClassName( "object_interior" )[0];
				}
				var g = ayoola.div.getParameterOptions( parameterOrOption, numberedSectionName );
				if( g.content ) 
				{
					p += g.content;
				}
				if( g.list ) 
				{
					q = q.concat( g.list );
				}
			//	alert( parameterOrOption );
				continue;
			}
			var parameterName = parameterOrOption.dataset.parameter_name;
			p += "&" + numberedSectionName + parameterName + "=";
		//	alert( parameterOrOption.outerHTML );
			var pattern = /\(/ig;
			var pattern = "x-x-x-xXXXxx";
			if( parameterOrOption.value != undefined )
			{ 
				//	encode so that & in links wont be affected.
				p += encodeURIComponent( parameterOrOption.value ).replace( pattern, "PC_SAFE_ITEMS_OPENING_BRACKET" ); 
			}
			else if( parameterOrOption.tagName.toLowerCase() == "form" )
			{ 
			//	alert( ayoola.div.getFormValues( { form: parameterOrOption, dontDisable: true } ) );
				p += encodeURIComponent( ayoola.div.getFormValues( { form: parameterOrOption, dontDisable: true } ) ).replace( pattern, "PC_SAFE_ITEMS_OPENING_BRACKET" ); 
			}
			else
			{ 
				p += encodeURIComponent( parameterOrOption.innerHTML ).replace( pattern, "PC_SAFE_ITEMS_OPENING_BRACKET" ); 
			}
			q.push( parameterName );
		}
	//	alert( q );
	//	alert( p );
		return { content: p, list: q };
	},

	//	Returns form values as query String
	getFormValues: function( formObject, dontDisable )
	{
		try
		{
			//	destroy all instances of ckeditor everytime state changes. So we can get the values from text area
			for( name in CKEDITOR.instances )
			{
				CKEDITOR.instances[name].destroy()
			}
		}
		catch( e )
		{
		
		}
		formObject = formObject.form ? formObject : { form: formObject, dontDisable: dontDisable }
/* 		switch( typeof formObject )
		{
			//	Compatibility
			case 'string':
				formObject = { form: formObject, dontDisable: dontDisable }
			break;
			case 'object':
			
			break;
		}
 */		formObject.buttonValue = formObject.buttonValue || 'Please wait...'; 
	//	get
		if( typeof formObject.form == 'string' ){ formObject.form = document.getElementById( formObject.form ); } 
		var query = '';
		//	alert( form.elements.length );
		for( var a = b = 0; a < formObject.form.elements.length; a++ )
		{
			var value = '';
			var element = formObject.form.elements[a];
			if( ! element.name ){ continue; }
			if( element.getAttribute( 'rel' ) && element.getAttribute( 'rel' ) == 'ignore' ){ continue; }
		//	alert( element.getAttribute( 'rel' ) );
		//	alert( element.type );
		//	alert( element.multiple );
		//	switch( element.type )
			{
			
			}
			var buildValue = function( v )
			{
				query += b == 0 ? '' : '&';
				query += element.name + '=' + encodeURIComponent( v );
				b++;
			}
			if( element.type == 'radio' || element.type == 'checkbox' )
			{ 
				value = element.checked ? element.value : value;
				
				var storage = storage || {};
				
			//	if( ! value ){ continue; }
				if( value )
				{ 
					buildValue( value );
				}
				else if( ! storage[element.name] )
				{ 
					// at lease send something denote empty
				//	buildValue( '----' ); 
				}
				storage[element.name] = true;
			}
			else if( element.multiple )
			{ 
				//	Select multiple
				for( var c = 0; c < element.options.length; c++ )
				{
					if( element.options[c].selected )
					{
						buildValue( element.options[c].value );
					}
				}
				
			}
			else
			{ 
				value = element.value; 
				buildValue( value );
			}
			
			//	Disable the element for 10sec.
			if( ! formObject.dontDisable )
			{
				if( element.type == 'submit'  )
				{
					element.value = formObject.buttonValue;
					element.innerHTML = formObject.buttonValue;
				}
			//	if( element.tagName == 'button'  )
				{
					
				}
				element.disabled = true;
				element.readonly = true;
			}
			
			//	enable elements.
			if( formObject.enableAll )
			{
				if( element.type == 'submit'  )
				{
					element.value = formObject.buttonValue;
					element.innerHTML = formObject.buttonValue;
				}
			//	if( element.tagName == 'button'  )
				{
					
				}
				element.disabled = false;
				element.readonly = false;
			}
			var enableElement = function()
								{ 
									element.disabled = false;
									element.readonly = false;
								}
			setTimeout( enableElement , 1000 );
		//	alert( element.type );
	//		alert( element.value );
		}
	//	alert( query );
		return query;
	},
		
	//	Sets a view to the container
	setToContainer: function( view, container, append )
	{
	//	var container = ayoola.post.container;
//		alert( typeof container );
		if( ! container ){ container = undefined; }
		switch( typeof container )
		{
			case 'string':
	//		alert( ayoola.post.container );
				var container = document.getElementById( container );
			case 'object':
			//	alert( container );
				if( ! append ){ container.innerHTML = ''; }
			//	container.id = '';
			//	alert( view.innerHTML );
				var span = document.createElement( 'span' );
			//	span.scrollIntoView();
			//	span.focus();
				container.appendChild( span );
				switch( typeof view )
				{
					case 'string':
			//		alert( ayoola.post.container );
						var span = document.createElement( 'span' );
					//	 span.appendChild( view );
						span.innerHTML = view;
						view = span;
	//				break;
					case 'object':
						if( container ){ view = container.appendChild( view ); }
					break;
				}
			break;
			default:
				ayoola.spotLight.close();
				ayoola.spotLight.popUp( view );
		}
//		view.scrollIntoView();
//		view.focus();
//		We can attempt to scroll to view in the calling method.
		return view;
	},
	//auto expand textarea
	autoExpand: function (h) {
		h.style.height = "20px";
		h.style.maxHeight = "80vh;";
		h.style.height = (h.scrollHeight)+"px";
	},
	//set value for form element globally. Useful in filemanager selection
	setFormElementValue: function ( element, value, id ) {
		switch( typeof element )
		{
			case 'string':

				var aa = document.getElementById( id );
				if( ! aa )
				{
					var a = document.getElementsByName( element );
				}
				else
				{
					a = Array( aa );
				}
				for( var b = 0; b < a.length; b++ )
				{
					a[b].value = value;
				//	b.className = ' normalnews';
				}
				var a = document.getElementsByName( id + '_preview_zone_image' );
				var xx = ayoola.pcPathPrefix + '/tools/classplayer/get/name/Application_IconViewer/?url=' + value;
				for( var b = 0; b < a.length; b++ )
				{
					a[b].src = xx;
				}
			
				if( window.parent )
				{
					var aa = window.parent.document.getElementById( id );
			//		alert( aa );  
					if( ! aa )
					{
						var a = window.parent.document.getElementsByName( element );
					}
					else
					{
						a = Array( aa );
					}
					for( var b = 0; b < a.length; b++ )
					{
						a[b].value = value;
					//	b.className = ' normalnews';
					}
					var a = window.parent.document.getElementsByName( id + '_preview_zone_image' );
					for( var b = 0; b < a.length; b++ )
					{
						a[b].src = xx;
					}
					try
					{
						window.parent.ayoola.spotLight.instance.container.parentNode.removeChild( window.parent.ayoola.spotLight.instance.container );
					}
					catch( e )
					{
						
					}
				}
			case 'object':
			break;
			default:
		}
			
	}

}
