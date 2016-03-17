<?php

class Ayoola_Validator_Username extends Ayoola_Validator_Abstract
{
	protected $_badnews;
	
    public function validate( $value )
    {
		if( ! is_string( $value ) )
		return false;
		
		if( $value === '' )		
		return false;	
		
		// Compare with the filtered value
		require_once 'Ayoola/Filter/Username.php';
		$filter = new Ayoola_Filter_Username;
		
		if( $value !== $filter->filter( $value ) )		
		return false;	
		
		return true;
    }
	
    public function getBadnews()
    {
        return '%value% consists of invalid characters. It should be a valid name';
    }
	
}
