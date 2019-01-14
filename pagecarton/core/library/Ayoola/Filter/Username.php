<?php

class Ayoola_Filter_Username implements Ayoola_Filter_Interface
{
	public $replace = '';

    public function filter( $value )
	{
		$pattern = '/[^a-zA-Z0-9-_.]/';
		$value = preg_replace( $pattern, $this->replace, (string) $value );
		$value = str_replace( array( $this->replace . $this->replace . $this->replace, $this->replace . $this->replace ), $this->replace, $value );

		return $value;   
	}
 
}
