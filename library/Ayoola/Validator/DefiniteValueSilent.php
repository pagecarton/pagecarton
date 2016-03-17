<?php

class Ayoola_Validator_DefiniteValueSilent extends Ayoola_Validator_DefiniteValue
{
	
    public function getBadnews()
    {
        return '%value% is invalid';
    }
}
