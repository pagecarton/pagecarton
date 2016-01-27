<?php

class Ayoola_Filter_DomainName implements Ayoola_Filter_Interface 
{

	
    public function filter( $domainName )
	{  
	//	exit( $domainName );
		
		//	debug
	//	$domainName = 'pagecarton.com';
		$domainName = str_ireplace( 'www.', '', strtolower( $domainName ) ); 
		$domainName = explode( ':', $domainName );
		$domainName = array_shift( $domainName );
		
		if( strpos( $domainName, '.document.' ) )
		{ 
			//	allow the document to look in the parent domain
			list( ,$domainName ) = explode( '.document.', $domainName );
	//		break; 
		} 
		return $domainName;
	}
 
}
