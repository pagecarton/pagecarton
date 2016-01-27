<?php

class Ayoola_Validator_CharacterWhitelist extends Ayoola_Validator_Abstract
{
	protected $_badnews;
	
    public function validate( $value )
    {
	//	var_export( $this );
		
		// Compare with the filtered value
		//require_once 'Ayoola/Filter/CharacterWhitelist.php';
		$filter = new Ayoola_Filter_CharacterWhitelist;
		$filter->validationParameters = $this->validationParameters;
		if( $value !== $filter->filter( $value) )		
		return false;	
		
		return true;
    }
	
    public function getBadnews()
    {
        return '%value% consists of invalid characters. It should be a valid CharacterWhitelist';
    }
	
}
