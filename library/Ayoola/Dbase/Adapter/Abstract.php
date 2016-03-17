<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4-1-2012 2.37pm ayoola $
 */

/**
 * @see 
 */
 
//require_once 'Ayoola/';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Ayoola_Dbase_Adapter_Abstract implements Ayoola_Dbase_Adapter_Interface
{
	
    /**
     * Database Info
     *
     * @var array
     */
	protected $_databaseInfo;
	
    /**
     * Constructor
     *
     * @param 
     * 
     */
    public function __construct()
    {
		$this->_dbSelect();
    }
	
    /**
     * This method sets _databaseInfo to a Value
     *
     * @param array Database Info
     * @return null
     */
    public function setDatabaseInfo( $databaseInfo )
    {		
		$databaseInfo = _Array( $databaseInfo );
		$this->_databaseInfo = $databaseInfo;
    } 
	
    /**
     * This method returns _databaseInfo
     *
     * @param string Info to Return
     * @return mixed Database Info
     */
    public function getDatabaseInfo( $key = null )
    {
		if( is_null( $this->_databaseInfo ) ){ $this->setDatabaseInfo(); }
		if( is_null( $key ) ){ return $this->_databaseInfo; }
		if( array_key_exists( $key, $this->_databaseInfo ) ){ return $this->_databaseInfo[$key]; }
		
		//	Error
		require_once 'Ayoola/Dbase/Adapter/Exception.php';
		throw new Ayoola_Dbase_Exception( 'Database Info Not Available - ' . $key );
    } 
	// END OF CLASS
}
