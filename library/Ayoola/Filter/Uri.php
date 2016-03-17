<?php

require_once 'Ayoola/Filter/Interface.php';

class Ayoola_Filter_Uri implements Ayoola_Filter_Interface
{

    public function filter( $value )
	{
		$value = (string) $value;
		if( stripos( $value, GET ) )
		{
			$value = substr( $value, 0, stripos( $value, GET ) );
		}
		$value = $value === '/' ? $value : rtrim( $value, '/' );
		return $value;
	}
 
}
