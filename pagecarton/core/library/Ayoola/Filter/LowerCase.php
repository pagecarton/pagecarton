<?php

class Ayoola_Filter_LowerCase implements Ayoola_Filter_Interface
{

    public function filter( $value )
	{
		$value = strtolower( $value );
		return $value;
	}
 
}
