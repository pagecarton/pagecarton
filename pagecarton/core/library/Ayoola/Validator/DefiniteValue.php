<?php

class Ayoola_Validator_DefiniteValue extends Ayoola_Validator_Abstract
{
	

	protected $_errorMessag;

	// A value will only pass validation if it is equal to $_value
    public function validate( $value )
    {
		// var_export( $value );
		return ( $value == $this->_value ) ? true : false;
	}
	
    public function setValue( $value )
    {
        $this->_value =  $value;
    }
	
    public function getBadnews()
    {
        return '%value% should be ' . $this->_value . ' ';
    }
	
	public function autofill( $parameters )
    {
		$this->setValue( $parameters[0] );
	//	var_export( $parameters );
    }
}
