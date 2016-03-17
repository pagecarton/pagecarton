<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: View.php 11.1.2011 time username $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_View
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_View
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
