<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Adapter_Cloud
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Cloud.php 1.23.12 1234am ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract
 * @see Ayoola_Access_Adapter_Interface
 */
 
require_once 'Ayoola/Dbase/Table/Abstract.php';
require_once 'Ayoola/Access/Adapter/Interface.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Access_Adapter_Cloud
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_Adapter_Cloud extends Ayoola_Access_Adapter_DbaseTable 
{
	
    /**
     * The table that holds the access credentials
     *
     * @var string
     */
	protected $_tableClassName = 'Application_User_CloudCopy';
	// END OF CLASS
}
