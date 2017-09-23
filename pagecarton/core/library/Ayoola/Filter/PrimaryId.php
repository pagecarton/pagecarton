<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_PrimaryId
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PrimaryId.php 10-25-2011 3.25pm ayoola $
 */

/**
 * @see Ayoola_Filter_Interface
 */
 
require_once 'Ayoola/Filter/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Filter_PrimaryId
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
 
class Ayoola_Filter_PrimaryId implements Ayoola_Filter_Interface
{
    /**
     * DbTable
     *
     * @var Ayoola_Dbase_Table_Interface
     */
	protected $_parameters; 

    /**
     * 
     *
     * @param void
     */
    public function filter( $value )
	{
		return Ayoola_Abstract_Table::getPrimaryId( $this->_parameters['table'], $this->_parameters['insert'], @$this->_parameters['select'] );
	}
	
    /**
     * For automated scripting of the filtering process
     *
     * @param mixed The Parameter
     * 
     */	
    public function autofill( array $parameters )
	{
		$this->_parameters = $parameters;
	}
 
}
