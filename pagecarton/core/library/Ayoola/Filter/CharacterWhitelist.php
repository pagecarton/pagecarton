<?php

class Ayoola_Filter_CharacterWhitelist implements Ayoola_Filter_Interface 
{

	public $replace = '';
	
    public function filter( $value )
	{
		$pattern = "/([^{$this->validationParameters['character_list']}])+/"; 
	//	var_export( $pattern );
	//	var_export( $this->validationParameters );
		$value = preg_replace( $pattern, $this->replace, (string) $value );
		return $value;
	}
 
}
