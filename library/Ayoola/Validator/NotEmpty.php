<?php

class Ayoola_Validator_NotEmpty extends Ayoola_Validator_Abstract
{
	protected $_badnews;
	
	protected $_value;
	
    public function validate( $value )
    {
	//	var_export( $value );
		if( @in_array( $value, $this->validationParameters['blacklist'] ) )
		{
			return false;
		}
		$this->_value = $value;
		if( $value === '0' ){ return true; }	
		return empty( $value ) ? false : true;
    }
	
    public function getBadnews()
    {
        return '%value% should not be empty ';
    }
	
}
