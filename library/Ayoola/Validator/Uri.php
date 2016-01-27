<?php

class Ayoola_Validator_Uri extends Ayoola_Validator_Abstract
{
	protected $_badnews;
	
    public function validate( $value )
    {
		if( ! is_string( $value ) )
		return false;
		
				
		if( $value === '' )	
		{
			if( false === @$this->validationParameters['required'] ) 
			{
				return true;
			}
			return false;	
		}
		
		if( $value[0] !== '/' )
		return false;
		
		$len = strlen( $value ); 
	//	var_export( $value );
//		var_export( $value[$len - 1] );
		
		if( $value[$len - 1] === '/' && $value !== '/' ){ return false; }

		if( strpos( $value, '//' ) !== false )
		return false;		
		
		if( strpos( $value, '.' ) !== false )
		return false;		
		
		
		// Compare with the filtered value
		//require_once 'Ayoola/Filter/Uri.php';
		$filter = new Ayoola_Filter_Uri;
		
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
        return '%value% must be of the format /path/to/file/';
    }
	
}
