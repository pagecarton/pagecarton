
//	Class Begins
ayoola.query =
{
	keywords: Array(), // keywords
	refreshResultSet: function()
	{
		var container = document.getElementById( "searchQuery_container_id" );
		//	alert( container.childNodes.length );
		for( var a = 0; a < container.childNodes.length; a++ )
		{
			var subContainer = container.childNodes[a];
			if( ! subContainer || ! subContainer.className ){ continue; }
	//		alert( subContainer.className );
			switch( subContainer.className )
			{
				case "searchQuery_subcontainer":
					break;
				case "searchQuery_keywords":
			//		alert( subContainer.innerHTML );
					ayoola.query.keywords = subContainer.innerHTML.split( /\b[\w+]+\b/ );
					continue;
					break;
				default :
					continue;
					break;
			}
			var searchContent = new Object;
			for( var b = 0; b < subContainer.childNodes.length; b++ )
			{
				var content = subContainer.childNodes[b];
				if( ! content || ! content.className )
				{
					continue;
				}
			//	alert( content.className );
				
				switch( content.className )
				{
					case "searchQuery_title":
						searchContent.title = content;
						break;
					case "searchQuery_link":
						searchContent.link = content;
						break;
					case "searchQuery_description":
						searchContent.description = content;
						break;
				}
			}
			var link = searchContent.link.innerHTML;
			
			//	Gets the content of the link
			var tempElement = document.createElement( 'div' );
			ayoola.xmlHttp.simulateFetchContentWithIframe( link, tempElement );
			searchContent.description.innerHTML = tempElement.innerHTML;
			
/* 			var changeContent = function()
			{
			
		//		alert( ajax.readyState );
				if( ! ayoola.xmlHttp.isReady( ajax ) ){ return false; } 
				var response = ajax.responseText; 
				var foundPos = null;
				var extractedContent = "";
				
				searchContent.title.innerHTML = 'chamged'; //response.substr( response.indexOf( /<title>/ ), response.indexOf( /<\/title>/ ) );	
 				for( var c = 0 ; c < ayoola.query.keywords.length; c++ )
				{
					var keyword = ayoola.query.keywords[c];
					do
					{
						var foundPos = response.lastIndexOf( keyword, foundPos );
						if( ! foundPos ){ break; } 
						extractedContent += response.substr( foundPos - 10, foundPos + 10  );
					}
					while( foundPos );
				}
 					
				searchContent.description.innerHTML = 'Changed Description';//extractedContent;
			}
 */		
		}
	},
	
	//	Hightlights the keywords in the search descriptions
	highlightKeywords: function()
	{
		ayoola.xmlHttp.setAfterStateChangeCallback( ayoola.query.highlightKeywords );
		var keywords = document.getElementById( 'query' );
		keywords = keywords.value;
		if( ! keywords ){ return false; }
		keywords = keywords.split( " " );
		var element = document.getElementById( "searchQuery_container_id" );
//		alert( element );
		for( var a in keywords )
		{
			if( ! keywords[a] ){ continue; } // skip empty spaces
			for( var b = 0; b < element.childNodes.length; b++ )
			{
				var subcontainer = element.childNodes[b];
				//	alert( subcontainer );
				if( ! ayoola.style.hasClass( subcontainer , "searchQuery_subcontainer" ) ){ continue; }
				//	alert( subcontainer );
				for( var c = 0; c < subcontainer.childNodes.length; c++ )
				{
					var child = subcontainer.childNodes[c];
		//		alert( "searchQuery_description" );
					if( ! ayoola.style.hasClass( child , "searchQuery_description" ) && ! ayoola.style.hasClass( child , "searchQuery_title" ) ){ continue; }
		//		alert( child );
					for( var d in child.childNodes )
					{
						if( ! child.childNodes[d].innerHTML ){ continue; } // skip irrelivant nodes
						var tempText = child.childNodes[d].innerHTML;
					//	alert( keywords[a] );
					//	alert( tempText );
						tempText = tempText.replace( keywords[a], "<strong>" + keywords[a] + "</strong>" );
				//		alert( tempText );
						child.childNodes[d].innerHTML = tempText;
					}
					
				}
			}
		}
				
	}
}