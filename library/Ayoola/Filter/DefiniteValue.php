<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_DefiniteValue
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: DefiniteValue.php 11-9-2011 4.55pm ayoola $
 */

/**
 * @see Ayoola_Filter_Interface
 */
 
require_once 'Ayoola/Filter/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_DefiniteValue
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
 
class Ayoola_Filter_DefiniteValue implements Ayoola_Filter_Interface
{
		
    /**
     * The value to set 
     *
     * @var mixed
     * @access protected
     */
	protected $_value = null;

    /**
     * Performs the filtering process
     *
     * @param mixed The value to be filtered
     * 
     */	
    public function filter( $value )
	{
//		var_export( htmlentities( $this->_value ) );
		return $this->_value;
	}
	
    /**
     * For automated scripting of the filtering process
     *
     * @param mixed The Parameter
     * 
     */	
    public function autofill( $parameters )
	{
		$value = empty( $parameters[0] ) ? : $parameters[0];
		$this->setValue( $value );
	}
	
    /**
     * Set the value property to a value
     *
     * @param void
     * @return mixed The value
     * 
     */	
    public function getValue()
	{
		return $this->_value;
	}
	
    /**
     * Set the value property to a value
     *
     * @param mixed The value
     * 
     */	
    public function setValue( $value )
	{
		$this->_value = $value;
	}
 
}
