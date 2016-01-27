<?php

class Ayoola_Validator_Digits extends Ayoola_Validator_Abstract
{
	protected $_badnews;
	
    public function validate( $value )
    {
		if( ! is_string( $value ) && ! is_int( $value ) && ! is_float( $value ) )
		return false;
		
		
		if( $value === '' )		
		return false;	

		// Compare with the filtered value
		//require_once 'Ayoola/Filter/Digits.php';
		$filter = new Ayoola_Filter_Digits;
		
		if( $value !== $filter->filter( $value) )		
		return false;	
		
		return true;
    }
	
    public function action()
    {
        // action body
    }
	
    public function getBadnews()
    {
        return '%value% can only consist of numbers';
    }
	
}
