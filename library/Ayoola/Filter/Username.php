<?php

class Ayoola_Filter_Username implements Ayoola_Filter_Interface
{

    public function filter( $value )
	{
		$pattern = '/[^a-zA-Z0-9-_.]/';
		$value = preg_replace( $pattern, '', (string) $value );
		return $value;
	}
 
}
