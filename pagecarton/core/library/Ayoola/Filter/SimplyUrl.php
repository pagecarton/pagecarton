<?php

require_once 'Ayoola/Filter/Interface.php';

class Ayoola_Filter_SimplyUrl implements Ayoola_Filter_Interface
{

	/**
	 * List of words to remove from URLs.
	 */
	public static $remove_list = array (
		'a', 'an', 'as', 'at', 'before', 'but', 'by', 'for', 'from',
		'is', 'in', 'into', 'like', 'of', 'off', 'on', 'onto', 'per',
		'since', 'than', 'the', 'this', 'that', 'to', 'up', 'via',
		'with'
    );

    public function filter( $value )
	{
		$value = (string) $value;
	//	var_export( $value );
	//	$value = iconv( "UTF-8", "ISO-8859-1//TRANSLIT", $value );
	//	var_export( $value );
		if( stripos( $value, GET ) )
		{
			$value = substr( $value, 0, stripos( $value, GET ) );
		}
		$value = $value === '/' ? $value : rtrim( $value, '/' );
        $value = preg_replace ('/\b(' . join ('|', self::$remove_list) . ')\b/i', '', $value);
		$remove_pattern = '/[^_\-.\-a-zA-Z0-9\s\/]/u';
		$value = preg_replace ($remove_pattern, '', $value); // remove unneeded chars
		$value = str_replace ('_', ' ', $value);             // treat underscores as spaces
		$value = preg_replace ('/^\s+|\s+$/u', '', $value);  // trim leading/trailing spaces
		$value = preg_replace ('/[-\s]+/u', '-', $value);    // convert spaces to hyphens
		$value = strtolower ($value);                        // convert to lowercase
		return $value;
	}
 
}
