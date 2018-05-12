<?php

class Ayoola_Filter_JsonDecode implements Ayoola_Filter_Interface
{
	
    public function filter( $value )
	{
		$value =  json_decode( $value );
		return $value;
	}
	
 
}
