<?php

class Ayoola_Filter_UnderscoreToSpace implements Ayoola_Filter_Interface
{

    public function filter( $value )
	{
		$value = str_replace( '_', ' ', $value );
		$value = ucwords( $value );
		
		return $value;
	}
 
}
