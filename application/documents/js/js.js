ayoola = {};
ayoola.artificialQueryString = ayoola.artificialQueryString ? ayoola.artificialQueryString : '';
ayoola.setArtificialQueryString = function( url )
{
	do
	{
		//	Retrieve the values after the /get/ in the url
	//	alert( url )
		var query = '';
		var artificialQueryString = '';
		url = url || location.pathname
		var path = url.split( '/get/' );
		if( path.length < 2 ){ break; }
	//	if( path.length < 2 ){ path = Array( '/', '/' ); }
		artificialGet = path.pop().split( '/' );
		var b = '';
		for( var a = 0; a < artificialGet.length; a++ )
		{
		//	alert( a );
			b = b == '=' ? '&' : '='
			if( a == 0 || a == artificialGet.length - 1 ){ b = ''; }
			artificialQueryString = artificialQueryString + b + artificialGet[a];
		}
		ayoola.artificialQueryString = artificialQueryString;
	//	alert( ayoola.artificialQueryString );
	}
	while( false )
		ayoola.artificialQueryString = location.search + ( location.search ? '&' : '?' ) + ayoola.artificialQueryString;
	//	alert( ayoola.artificialQueryString );
}
ayoola.setArtificialQueryString();