<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Cloud
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Cloud.php 1.23.12 8.11 ayoola $
 */

/**
 * @see Ayoola_Dbase_Adapter_Interface
 */
 
require_once 'Ayoola/Dbase/Adapter/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Dbase_Adapter_Cloud
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Dbase_Adapter_Cloud extends Ayoola_Dbase_Adapter_Abstract
{

    /**
     * Constructor
     *
     * @param array Database Info
     * 
     */
    public function __construct( $databaseInfo = null )
    {
		if( ! is_null( $databaseInfo ) ){ $this->setDatabaseInfo( $databaseInfo ); }
    }
	
    /**
     * calls the ayoola cmf api for cloud connection
     *
     * @return mixed
     */
/*     public function __call( $name, $arguments ) 
    {
		return Ayoola_Api::send( $arguments );
    } 
 */
	// END OF CLASS
}
