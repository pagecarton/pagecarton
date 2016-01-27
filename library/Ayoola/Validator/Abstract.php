<?php

abstract class Ayoola_Validator_Abstract implements Ayoola_Validator_Interface
{

	protected static $_instance;
	protected static $_filter;
	protected $_valid;
	protected $_badnews;
	public $validationParameters;

    /**
     * 
     *
     * @var string
     */
	protected $_parameters;

    /**
     * Value for validation
     *
     * @var mixed
     */
	protected $_value;
	
    public function __construct()
    {
        /* Initialize action controller here */
    }

    public function validate( $value )
    {
        // action body
    }
	
    public function getBadnews()
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
	
	public function autofill( $parameters )
    {
		//$args = array_slice( $args, 0, 2 );
		$this->validationParameters = $parameters;
    }
}
