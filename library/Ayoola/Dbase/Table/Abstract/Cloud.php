<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Table_Abstract_Cloud
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Cloud.php 4.9.12 11.52 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract
 * @see Ayoola_Dbase_Table_Abstract_Exception
 */
 
require_once 'Ayoola/Dbase/Table/Abstract.php';
require_once 'Ayoola/Dbase/Table/Abstract/Exception.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Table_Abstract_Cloud
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Dbase_Table_Abstract_Cloud extends Ayoola_Dbase_Table_Abstract implements Ayoola_Dbase_Table_Interface
{
	
    /**
     * Constructor
     *
     * @param 
     * 
     */
    public function __construct(){ $this->init(); }
	
    /**
     * Initialize the Table
     *
     * @param void
     */
    public function init()
    {
		//	We are using the Cloud Adapter
		$database = new Ayoola_Dbase( array( 'adapter' => 'Cloud' ) );
		parent::__construct( $database );
				
   }

    /**
     * Overloading the Methods
     *
     * @throws Ayoola_Dbase_Exception
     */
    public function __call( $name, $arguments ) 
	{
	//	$arguments[] = $arguments;
		$newArguments = array();
		$newArguments[] = array( 'request_type' => 'Dbase', 'domain_name' => Ayoola_Page::getDefaultDomain(), 'function_name' => $name, 'class_name' => get_class( $this ) );
		$newArguments[] = $arguments;
	//	var_export( $arguments );
		$response = Ayoola_Api::send( $newArguments );
		return $response;
///		return call_user_func_array( array( $this->getDatabase()->getAdapter(), $name ), $newArguments );
    }
	// END OF CLASS
}
