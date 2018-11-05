<?php

class Ayoola_Filter_FileExtention implements Ayoola_Filter_Interface
{

    public function filter( $value )
	{
/* 		$ext = substr( $value, strrpos( $value, '.' ) );
		$ext = strtolower( $ext );
 */		
		$value = explode( '.', $value );
		$value = strtolower( array_pop( $value ) );
		return $value;
	}
 
}
