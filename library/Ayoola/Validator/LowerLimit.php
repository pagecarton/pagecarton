<?php

class Ayoola_Validator_LowerLimit extends Ayoola_Validator_Abstract
{
	protected $_min = 1;
	protected $_inclusive = true;
	protected $_badnews;
	
	
	
    public function validate( $value )
    {
		if( ! $this->_inclusive )
		return ( $value <= $this->_min ) ? false : true;
		if( $this->_inclusive )
		return ( $value < $this->_min ) ? false : true;		
		return false;
    }
	
    public function setLimit( $min )
    {
        $this->_min = $min;
    }
	
    public function setInclusive( $switch = true )
    {
        $this->_inclusive =  (boolean) $switch;
    }
	
    public function getBadnews()
    {
        return '%value% should be at least ' . $this->_min;
    }
	
	public function autofill( $parameters )
    {
		//$parameters = array_slice( $parameters, 0, 1 );
		$this->setLimit( $parameters[0] );
    }
}
