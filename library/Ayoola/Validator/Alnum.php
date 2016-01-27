<?php

class Ayoola_Validator_Alnum extends Ayoola_Validator_Abstract
{
	protected $_badnews;
	protected $_startWithAlpha = false;
	
    public function validate( $value )
    {
		if( ! is_string( $value ) && ! is_int( $value ) && ! is_float( $value ) )
		return false;
		
		if( $value === '' )		
		return false;	

		if( $this->_startWithAlpha )
		{
			if( $value[0] > 0 || $value[0] === '0' )
			return false;	
		}
		// Compare with the filtered value
		//require_once 'Ayoola/Filter/Alnum.php';
		$filter = new Ayoola_Filter_Alnum;
	//	var_export( $filter->filter( $value) ); 
		
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
        return '%value% can only consist of alphabets and numbers';
    }
	
}
