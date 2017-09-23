<?php

class Ayoola_Filter_Alnum implements Ayoola_Filter_Interface  
{

	public $replace = '';

    public function filter( $value )
	{
		$pattern = '/[^a-zA-Z0-9]/';
		$value = preg_replace( $pattern, $this->replace, (string) $value );
		return $value;
	}
 
}
