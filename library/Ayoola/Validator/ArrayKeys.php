<?php

class Ayoola_Validator_ArrayKeys extends Ayoola_Validator_InArray
{
	
	
    public function validate( $value )
    {
	//	var_export( $value );
	//	var_export( $this->_array );
		return  array_key_exists( $value, $this->_array );
	}
	
}
