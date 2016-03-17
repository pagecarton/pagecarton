<?php

class Ayoola_Filter_Trim implements Ayoola_Filter_Interface
{

    public function filter( $value )
	{
		$value = trim( $value );
		return $value;
	}
 
}
