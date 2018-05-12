<?php

class Ayoola_Filter_JsonEncode implements Ayoola_Filter_Interface
{
	
    public function filter( $value )
	{
		$value =  json_encode( $value );
		return $value;
	}
	
 
}
