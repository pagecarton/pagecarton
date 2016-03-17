<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   Application
 * @package    Application_PhoneNumber
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: PhoneNumber.php date time ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Mysql
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Mysql.php';


/**
 * @category   PageCarton CMS
 * @package    Application_PhoneNumber
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_PhoneNumber extends Ayoola_Dbase_Table_Abstract_Mysql
{	
	
    /**
     *
     * @var boolean
     */
	protected static $_allowApiAccess = true;
	// END OF CLASS
}
