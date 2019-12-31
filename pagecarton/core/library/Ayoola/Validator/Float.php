<?php

class Ayoola_Validator_Float extends Ayoola_Validator_Abstract
{
	protected $_badnews;
	
    public function validate( $value )
    {
		if( ! is_string( $value ) && ! is_int( $value ) && ! is_float( $value ) )
		return false;
		
		if( is_float( $value ) )
		return true;
		if( strval( floatval( $value ) ) === $value )
		return true;
		
		return false;
    }
	
    public function action()
    {
        // action body
    }
	
    public function getBadnews()
    {
        return '%value% can only consist of float values';
    }
	
}
