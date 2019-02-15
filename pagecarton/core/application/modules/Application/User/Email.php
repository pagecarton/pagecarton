<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Application
 * @package    Application_User_Email
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Email.php 9-10-2012 11.40 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract
 */
 
require_once 'Ayoola/Dbase/Table/Abstract.php';

/**
 * @user   Ayoola
 * @package    Application_User_Email
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_Email extends Ayoola_Dbase_Table_Abstract_Mysql
{
		
    /**
     * The available emails for the account
     * 
     * @var array
     */
	protected $_availableEmails = array();
	// END OF CLASS
}
