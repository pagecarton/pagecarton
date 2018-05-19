<?php

class Ayoola_Validator_WordCount extends Ayoola_Validator_Abstract
{
	
	protected $_max = 100;
	protected $_min = 1;
	protected $_inclusive = true;
	protected $_errorMessag;
	
	
	
    public function validate( $value )
    {
	//	var_export( $value );
		$value = strlen( (string) $value );
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
        return '%value% should be between ' . $this->_min . ' and ' . $this->_max . ' characters.';
    }
	
	public function autofill( $parameters )
    {
		//$args = array_slice( $args, 0, 2 );
  //      var_export( $parameters );
		$this->setRange( $parameters[0],$parameters[1] );
    }
}
