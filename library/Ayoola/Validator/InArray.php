<?php

class Ayoola_Validator_InArray extends Ayoola_Validator_Abstract
{
	protected $_array = array();

	

	protected $_errorMessag;
	
	
	
    public function validate( $value )
    {
		return  in_array( $value, $this->_array ) ? true : false;

	}
	
    public function setArray( Array $array )
    {
        $this->_array =  (array) $array;
    }
	
    public function getBadnews()
    {
        return 'Please select a valid value for %value%.';
    }
	
	public function autofill( $parameters )
    {
		$parameters = _Array( $parameters );
		$this->setArray( $parameters );
		
    }
}
