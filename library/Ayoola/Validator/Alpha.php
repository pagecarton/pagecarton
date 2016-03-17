<?php

class Ayoola_Validator_Alpha extends Ayoola_Validator_Abstract
{
	protected $_badnews;
	
    public function validate( $value )
    {
		if( ! is_string( $value ) )
		return false;
		
		if( $value === '' )		
		return false;	
		
		// Compare with the filtered value
		//require_once 'Ayoola/Filter/Alpha.php';
		$filter = new Ayoola_Filter_Alpha;
		
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
        return '%value% should be alphabetic';
    }
	
}
