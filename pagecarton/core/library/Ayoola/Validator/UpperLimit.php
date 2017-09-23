<?php

class Ayoola_Validator_UpperLimit extends Ayoola_Validator_Abstract
{
	protected $_max = 100;
	protected $_inclusive = true;
	protected $_badnews;
	
    public function validate( $value )
    {
		if( ! $this->_inclusive )
		return ( $value >= $this->_max ) ? false : true;
		if( $this->_inclusive )
		return ( $value > $this->_max ) ? false : true;		
		return false;
    }
	
    public function setLimit( $max )
    {
        $this->_max = $max;
    }
	
    public function setInclusive( $switch = true )
    {
        $this->_inclusive =  (boolean) $switch;
    }

    public function getBadnews()
    {
        return '%value% should not be more than ' . $this->_max;
    }
	
	public function autofill( $parameters )
    {
		$parameters = array_slice( $parameters, 0, 1 );
		$this->setLimit( $parameters[0] );
    }
}
