<?php

class Ayoola_Filter_Float implements Ayoola_Filter_Interface
{

    public function filter( $value )
	{
		$value = (float) strval( $value );
		return $value;
	}
 
}
