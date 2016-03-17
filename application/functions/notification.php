<?php 

// My first function


// Function to display error messages accross all pages. 
function showBadnews( Array $badnews )
{
	if ( empty( $badnews ) ){ return; }
	$xml = new Ayoola_Xml();
	$doc = $xml->createElement( 'ul' );
	foreach ($badnews as $alert)
	{
		if( ! $alert ){ continue; }
		$list = $xml->createElement( 'li', $alert );
		$list->setAttribute( 'class', 'badnews' );
		$doc->appendChild( $list );
	}
	$xml->appendChild( $doc );
	return $xml->saveHTML();
}

// Function to display message (goodNews) accross all pages. 
function showgoodnews()
{
	GLOBAL $goodnews;
	if (isset($goodnews) && !empty($goodnews)) 
	{
		echo '<div class="goodnews"><ul>';
	foreach ($goodnews as $message) 
	{
		echo "<li>$message</li>\n";
	}
		echo '</ul></div>';
	}
}



?>