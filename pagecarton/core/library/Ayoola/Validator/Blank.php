<?php

class Ayoola_Validator_Blank extends Ayoola_Validator_Abstract
{
	protected $_badnews;
	
	protected $_value;
	
    public function validate( $value )
    {
	//	var_export( $value );
		return empty( $value ) ? true : false;
    }
	
    public function getBadnews()
    {
        return '%value% should be left blank';
    }
	
}
