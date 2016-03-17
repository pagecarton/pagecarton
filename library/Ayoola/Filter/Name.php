<?php

class Ayoola_Filter_Name implements Ayoola_Filter_Interface 
{

	public $replace = '';
	
    public function filter( $value )
	{  
		$pattern = "/([^0-9a-zA-Z_])+/"; 
		$value = preg_replace( $pattern, $this->replace, (string) $value );
		return $value;
	}
 
}
