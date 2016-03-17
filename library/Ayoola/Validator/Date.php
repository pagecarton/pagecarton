<?php

class Ayoola_Validator_Date extends Ayoola_Validator_Abstract
{
	protected $_badnews;
	protected $_format= 'MMDDYYYY'; 
	protected $_separator= '-'; 
	protected $_structure = array( 	'MM' => 0, 
									'DD' => 2, 
									'YYYY' => 4 );

	
    public function validate( $value )
    {
//		echo $value;
		if( ! is_string( $value ) && ! is_int( $value ) && ! is_float( $value ) )
			return false;
		
		if( strlen( $value ) < 8 || strlen( $value ) > 10 )		
			return false;
		$value =  str_replace( $this->_separator, '', $value );	
//		echo $value;
//		echo $this->_separator;
		
		if( strlen( $value ) !== 8 )		
			return false;
		
		//require_once 'Ayoola/Filter/Digits.php';
		$filter = new Ayoola_Filter_Digits;
		$value =  $filter->filter( $value );		

		$month = substr( $value, $this->_structure['MM'], 2);
		$day = substr( $value, $this->_structure['DD'], 2);
		$year = substr( $value, $this->_structure['YYYY'], 4);
		
		if( checkdate( $month, $day, $year ) )
		return true;
		

	}
	
    public function setFormat( $format, $separator = '-' )
    {
		$format = strtoupper( (string) $format );
		$this->_separator = is_string( $separator ) ? $separator : $this->_separator;
		
		require_once 'Ayoola/Filter/Alpha.php';
		$filter = new Ayoola_Filter_Alpha;
		$format =  $filter->filter( $format );		

		if( strlen( $format ) !== 8 )		
			return false;

		if( stripos( $format, 'YYYY' ) === false 
		|| 	stripos( $format, 'DD' ) === false 
		||	stripos( $format, 'MM' ) === false )
			return false;
			
		$this->_format = $format;

		
		$year = stripos( $format, 'YYYY' );
		$date = stripos( $format, 'DD' );
		$month = stripos( $format, 'MM' );
		$format = array( 
						'MM' => $month, 
						'DD' => $date, 
						'YYYY' => $year, 
						);
		asort ( $format );
		return $this->_structure = $format;
		
    }
		
    public function getBadnews()
    {
		$message = '%value% should be of the format ' . $this->_format . ' or ';
		$format= '';
		foreach( $this->_structure as $key => $value ):
			$format .= $key . $this->_separator;
			endforeach;
		$format = rtrim( $format, $this->_separator );
		$message .= $format;
        return $message;
    }
	
	public function autofill( $parameters )
    {
		$this->setFormat( $parameters[0] , isset( $parameters[1] ) ? : '-' );
    }
	
}
