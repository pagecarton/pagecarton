<?php

class Ayoola_Filter_Currency implements Ayoola_Filter_Interface
{
	
    /**
     * The Symbol that Shows with the currency
     * 
     * @param string
     */
	public static $symbol;

    public function filter( $value )
	{
		$value = floatval( $value );
		$value = sprintf( "%.2f", $value );
		//var_export( $value );
		return self::$symbol . $value;
	}

}
