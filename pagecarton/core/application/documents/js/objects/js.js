//	Class Begins
ayoola.js =
{
	addCodeToHead: false, // whether to add code to the top or to the bottom.
	delayLoading: false, // whether to add code to the top or to the bottom.
		
	//	Add a javascript code to the body
	addCode: function( code, returnScript )
	{
		var script = document.createElement( 'script' );
		script.setAttribute( 'type', 'text/javascript' );
		script.innerHTML = code;
		if( returnScript )
		{
			return script;
		}
		ayoola.js.addToBody( script, true  );
	},
		
	//	Add a javascript code to the body
	addCodeOnLoad: function( code )
	{
		ayoola.js.addToBody( ayoola.js.addCode( code, true ), true, true );
	},

	hashCode: function( text )
	{
		var hash = 0, i, chr;
		if ( text.length === 0 ) 
		{
			return hash;
		}
		for( i = 0; i < text.length; i++ ) 
		{
			if( ! text.charCodeAt )
			{
				continue;
			}
			chr   = text.charCodeAt(i);
			hash  = ((hash << 5) - hash) + chr;
			hash |= 0; // Convert to 32bit integer
		}
		return hash;
	},		
	//	Add a javascript file to the body
	addFile: function( file, returnScript )
	{
		var script = document.createElement( 'script' );
		script.setAttribute( 'src', file );
		if( returnScript )
		{
			return script;
		}
		ayoola.js.addToBody( script, true  );
	},
		
	//	Add a javascript file to the body onload
	addFileOnLoad: function( file )
	{
		ayoola.js.addToBody( ayoola.js.addFile( file, true ), true, true );
	},
		
	//	Add a javascript file to the body
	addToBody: function( script, addCodeToHead, delayLoading )
	{
		//	Ensure this is not a double addition
		var scriptsInDocument = document.getElementsByTagName( 'script' );
		if( scriptsInDocument && scriptsInDocument.length )
		{
			for( var a = 0; a < scriptsInDocument.length; a++ )
			{
				var currentScript = scriptsInDocument[a];
				if( currentScript.innerHTML == script.innerHTML && script.src == currentScript.src )
				{
				//	alert( currentScript.src );
					return false;
				}
			
			}
		}
		var parentElement = document.body;
		if( addCodeToHead )
		{
			var head = document.getElementsByTagName( 'head' );
			if( head && head.item( 1 ) ){ parentElement = head.item( 1 ); }
		}
	//	alert( script );
	//	alert( script.src );
		if( ! delayLoading )
		{
			parentElement.appendChild( script );
		}
		else
		{
			ayoola.events.add( window, 'load', function(){ parentElement.appendChild( script ); } );
		}
	},
}
ayoola.storage =
{
    store: function( key, value )
    {
        if( typeof( Storage ) !== "undefined" )
        {
            return localStorage.setItem( key, JSON.stringify( value ) );
        }
    },
    merge: function( key, value )
    {
    //    alert( key );
    //    alert( value );
    //    alert( typeof( value ) );
        if( typeof( Storage ) !== "undefined" )
        {
            var a = ayoola.storage.retrieve( key );
            if( ! a  || ! a.indexOf )
            {
                a = [];
            }
            if( a.indexOf( value ) == -1 )
            {
                a.unshift( value );
            }
            
            return ayoola.storage.store( key, a );
        }
    },
    retrieve: function( key )
    {
        if( typeof( Storage ) !== "undefined" )
        {
            return JSON.parse( localStorage.getItem( key ) );
        }
    }
}
