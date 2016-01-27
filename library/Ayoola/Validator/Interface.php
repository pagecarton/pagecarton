<?php

interface Ayoola_Validator_Interface
{
// My Validators implementing this must have the following methods

    public function validate( $value ); 
	
    public function getBadnews();
	 
}
