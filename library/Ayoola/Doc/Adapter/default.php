<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Ayoola_
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.ayo-ola.com)
 * @license    http://developer.ayo-ola.com/aystyle/license/
 * @version    $Id: filename.php date time username $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   Ayoola
 * @package    Ayoola_
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.ayo-ola.com)
 * @license    http://developer.ayo-ola.com/aystyle/license/
 */

class ClassName
{
    /**
     * property
     *
     * @var boolean
     */
	protected $property;
	
    /**
     * Singleton instance
     *
     * @var self()
     */
	protected $_instance;

    /**
     * Constructor
     *
     * @param 
     * 
     */
    public function __construct()
    {

    }
	
    /**
     * This method
     *
     * @param 
     * @return 
     */
    public function method()
    {
        
    } 
	
    /**
     * This method makes sure there is only a single instance
     * of this class.
     * @param void
     * @return __CLASS__
     */
	public static function getInstance()
    {
		return is_null($this->instance) ? new self() : $this->instance; 
    } 
	
    public function __get($property)
    {
    }    
    public function __set($property, $value)
    {
    }
    public function __call($method, $args)
    {
    }
    public function __toString()
    {
	}  
	
	// END OF CLASS
}
