<?php

class Ayoola_Filter_FileExtention implements Ayoola_Filter_Interface
{

    public function filter( $value )
	{
/* 		$ext = substr( $value, strrpos( $value, '.' ) );
		$ext = strtolower( $ext );
 */		
		
		return strtolower( array_pop( explode( '.', $value ) ) );
	}
 
}
