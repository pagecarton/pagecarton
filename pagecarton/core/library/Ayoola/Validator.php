<?php

abstract class Ayoola_Validator implements Ayoola_Validator_Interface
{
	protected static $_instance;
	protected $_valid;
	protected $_badnews;
	
    public function __construct()
    {
        /* Initialize action controller here */
    }
	    public static function getInstance()
    {
        // action body
		return is_null($this->_instance) ? new self() : $this->_instance; 
		
    }

    public function notEmpty( $value )
    {
        // action body
    }
    public function trim( $value )
    {
        // action body
    }
    public function numbers()
    {
        // action body
    }
    public function __get($property)
    {
        // Returns a value of a property
    }    
    public function __set($property, $value)
    {
        // Set the object property
    }
    public function __call($method, $args)
    {
        // Calling the object method.
    }
    public function __toString()
    {
        // Text translation string
    }  
}
