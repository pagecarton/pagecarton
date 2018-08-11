<?php

class Ayoola_Validator_EmailAddress extends Ayoola_Validator_Abstract
{
	protected $_badnews;
	protected $_username; 
	protected $_hostname; 
	protected $_tld; 

	
    public function validate( $value )
    {
	//	var_export( $value );
		if( ! is_string( $value ) )
			return false;
		if( substr_count( $value, '@' )  > 1 )		
			return false;

		if( strpos( $value, ' ' ) !== false )		
			return false;
		
		if( substr_count( $value, '.' )  < 1 )		
			return false;
		
		if( substr_count( $value, '@.' )  > 0 )		
			return false;
		
		if( strpos( $value, '.' ) < 1 || strpos( $value, '@' ) < 1 )		
			return false;
				
	//require_once 'Ayoola/Filter/FileExtention.php';
		$filter = new Ayoola_Filter_FileExtention;
		$tld =  $filter->filter( $value );
		$this->_tld = $tld;
		//	var_export( strlen( $tld ) );		
		
		//	.online didn't work
//		if( strlen( $tld ) < 2 || strlen( $tld ) > 5 )	
//			return false;
		
		list( $this->_username, $this->_hostname ) = explode( '@', $value, 2 );
		
		//	Check against allowed lenght
		if( strlen( $this->_username ) > 64 || $this->_hostname > 255 )		
			return false;
		
		return true;
		

	}
	
    public function getBadnews()
    {
		return '%value% seems to be an invalid email address';
    }
	
	
}
