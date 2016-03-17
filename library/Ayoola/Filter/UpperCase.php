<?php

class Ayoola_Filter_UpperCase implements Ayoola_Filter_Interface
{

    public function filter( $value )
	{
		$value = strtoupper( $value );
		return $value;
	}
 
}
