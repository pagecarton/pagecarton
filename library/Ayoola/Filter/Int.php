<?php

class Ayoola_Filter_Int implements Ayoola_Filter_Interface
{

    public function filter( $value )
	{
		$value = (int) strval( $value );
		return $value;
	}
 
}
