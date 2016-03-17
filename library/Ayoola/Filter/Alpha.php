<?php

class Ayoola_Filter_Alpha implements Ayoola_Filter_Interface
{

	public $replace = '';

    public function filter( $value )
	{
		$pattern = '/[^a-zA-Z]/';
		$value = preg_replace( $pattern, $this->replace, (string) $value ); 
		return $value;
	}
 
}
