<?php

class Ayoola_Filter_CharacterWhitelist implements Ayoola_Filter_Interface 
{

	public $replace = '';
	
    public function filter( $value )
	{
		$pattern = "/([^{$this->validationParameters['character_list']}])+/"; 

        $replace = $this->validationParameters['replace'] ? : $this->replace;
		$value = trim( preg_replace( $pattern, $replace, (string) $value ), $replace );
		return $value;
	}
 
}
