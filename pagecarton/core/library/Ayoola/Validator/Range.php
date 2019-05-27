<?php

class Ayoola_Validator_Range extends Ayoola_Validator_Abstract
{
	protected $_min = 1;
	protected $_max = 100;
	protected $_inclusive = true;
	protected $_badnews;
	
	
	
    public function validate( $value )
    {
		if( ! $this->_inclusive )
		return ( $value <= $this->_min  || $value >= $this->_max ) ? false : true;
		if( $this->_inclusive )
		return ( $value < $this->_min  || $value > $this->_max ) ? false : true;
		return false;
    }
	
    public function setRange( $min, $max )
    {
        $this->_min = $min;
        $this->_max = $max;
    }
	
    public function setInclusive( $switch = true )
    {
        $this->_inclusive =  (boolean) $switch;
    }
	
    public function getBadnews()
    {
        return '%value% should be in the range of ' . $this->_min . ' and ' . $this->_max;
    }

    public function action()
    {
        // action body
    }
   
   public function autofill( $parameters )
    {
		//$args = array_slice( $args, 0, 2 );
		$this->setRange( $parameters[0],$parameters[1] );
    }
	
   public function __call( $method, $args )
    {
		//$args = array_slice( $args, 0, 2 );
		$this->setRange( $args[0],$args[1] );
    }

}
