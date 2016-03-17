<?php

class Ayoola_Validator_TodaysDate extends Ayoola_Validator_Abstract
{
	protected $_today = 100;
	protected $_min = 1;
	
	protected $_inclusive = true;
	protected $_errorMessag;
	
	
	
    public function validate( $value )
    {
		$this->_today = time();
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
        return '%value% should contain between ' . $this->_min . ' and ' . $this->_max . ' characters';
    }
	
	public function autofill( $parameters )
    {
		//$args = array_slice( $args, 0, 2 );
		$this->setRange( $parameters[0],$parameters[1] );
    }
}
