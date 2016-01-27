<?php

class Ayoola_Filter_Digits implements Ayoola_Filter_Interface
{

    public function filter( $value )
	{
		$pattern = '/[^0-9]/';
		$value = preg_replace( $pattern, '', (string) $value );
		return $value;
	}
 
}
