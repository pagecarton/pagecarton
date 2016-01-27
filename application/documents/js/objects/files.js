//	Class Begins

ayoola.files =
{
	jsObjectPath: '/js/objects/',	//	Path to javascript objects
		
	//	Load a file into the document
	loadFile: function( filename, filetype )
	{
		filetype = filetype || this.getExtention( filename );
		
		var fileNode = this.getFileNode( filename, filetype );
		
			//alert( fileNode.href || fileNode.src );
		return fileNode ? this.setFileNode( fileNode ) : false;
	},
		
	//	Load a file into the document
	loadJsObject: function( objectName )
	{		
		var filename = this.getJsObjectPath( objectName ).filename;
		var result = this.loadFile( filename, 'js' );
		
		//alert( window[objectName] );
		//	Make sure that script is loaded before you continue
		//while( ! this[this[objectName]] ){ alert( objectName ); }
		
		return result;
	},
		
	//	Load a file into the document
	loadJsObjectCss: function( objectName, css )
	{		
		css = css || 'css.css';
		var filename = this.getJsObjectPath( objectName ).path + css;
		
		return this.loadFile( filename, 'css' );
	},
		
	//	split filename into extention and file
	setFileNode: function( fileNode )
	{
		var tag = fileNode.tagName;
		if( tag == 'SCRIPT' || 'LINK' )
		{
			// Add Javascript and CSS to the HEAD TAG
			var parent = document.getElementsByName( 'head' )[0] || document.lastChild;
			//	alert( parent );
			parent.appendChild ( fileNode );
		}
			//	alert( tag );
	},
		
	//	split filename into extention and file
	getFileNode: function( filename, filetype )
	{
		var node = null;
		if( filetype == 'js' )
		{
			node = document.createElement( 'script' );
			node.src = filename;
			node.type = 'text/javascript';
		}
		else if( filetype == 'css' )
		{
			node = document.createElement( 'link' );
			node.href = filename;
			node.type = 'text/css';
			node.rel = 'stylesheet';
		}
		if( ! node ){ return false; }
		//	Check against duplicate loading
		var elements = document.getElementsByTagName( node.tagName );
				//alert( elements.length );
		for( var a = 0; a < elements.length; a++ )
		{
			//alert( elements[a].src );
			if( ( elements[a].src && node.src && elements[a].src == node.src ) || ( elements[a].href && node.href && elements[a].href == node.href ) ) 
			{ 
				//alert( elements[a].src + node.src ); 
				return false; 
			}
			//alert( elements[a].src ); 
		}
		
		return node;
	},
		
	//	Turn an object name into a complete filename
	getJsObjectPath: function( objectName )
	{
		var filename = this.jsObjectPath + objectName + '.js';
		var path = this.jsObjectPath + objectName + '/';
		return { filename:filename, path:path };
	},
		
	//	split filename into extention and file
	getExtention: function( filename )
	{
		filename = filename.split( '.' );
		var a = filename.length;
		if( a < 2 ) { return false; } 	// smallest could be 'file.ext'
		var extention = filename[--a];	//	Reduce it since array starts from zero
		
		return extention;
	}
}
