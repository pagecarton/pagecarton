<?php

class Ayoola_Filter_Escape implements Ayoola_Filter_Interface
{
	
    public function filter( $value )
	{
		$value =  addslashes( $value );
		return $value;
	}
	
 
}
