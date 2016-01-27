<?php

class Ayoola_Validator_MinMax extends Ayoola_Validator_Abstract
{
	
	protected $_max = 100;
	protected $_min = 1;
	protected $_inclusive = true;
	protected $_errorMessag;
	
	
	
    public function validate( $value )
    {
	//	var_export( $value ); 
		$value = (int) $value;
		if( ! $this->_inclusive )
		return ( $value >= $this->_max || $value <= $this->_min ) ? false : true;
		if( $this->_inclusive )
		return ( $value > $this->_max || $value < $this->_min ) ? false : true;		
		return false;
    }
	
    public function setRange( $min, $max )
    {
        $this->_max = $max;
        $this->_min = $min;
    }
	
    public function setInclusive( $switch = true )
    {
        $this->_inclusive =  (boolean) $switch;
    }
	
    public function getBadnews()
    {
        return 'Minimum value for %value% is ' . $this->_min . ' while the maximum value is ' . $this->_max . '.';
    }
	
	public function autofill( $parameters )
    {
		//$args = array_slice( $args, 0, 2 );
		@$this->setRange( $parameters[0] ? : 1,$parameters[1] ? : 100 );  
    }
}
