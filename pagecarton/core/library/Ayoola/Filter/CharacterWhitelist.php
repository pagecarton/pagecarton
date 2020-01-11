<?php

class Ayoola_Filter_CharacterWhitelist implements Ayoola_Filter_Interface 
{

	public $replace = '';
	
    public function filter( $value )
	{
		$pattern = "/([^{$this->validationParameters['character_list']}])+/"; 
	//	var_export( $pattern );
	//	var_export( $this->validationParameters );
		$value = trim( preg_replace( $pattern, $this->validationParameters['replace'] ? : $this->replace, (string) $value ), $this->replace );
		return $value;
	}
 
}
