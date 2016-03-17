<?php
	require_once 'Ayoola/Filter/Interface.php';
	
class Ayoola_Filter_Get implements Ayoola_Filter_Interface
{

    public function filter( $url )
	{
		defined('GET') || define('GET', "/get/");
		$query = array();
		// Try to modulate get parameters
		if( stripos( $url, GET ) )
		{
			$url = str_replace( GET, '', substr( $url, stripos( $url, GET ) ) );
		//	var_export( $url ); 
			$url = explode( '/', trim( $url, '/' ) );
			$i = 0;
			$query = array();
			while( $i < count( $url ) )
			{
			//	var_export( $i );  
				$query[$url[$i++]] = $url[$i++];
			}
		//	var_export( $url );  
		//	var_export( $query );  
		}	
		return $query;
	}
 
}
   