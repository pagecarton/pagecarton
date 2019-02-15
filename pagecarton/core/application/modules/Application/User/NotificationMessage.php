<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_NotificationMessage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: NotificationMessage.php date time ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml_Protected
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @user   Ayoola
 * @package    Application_User_NotificationMessage
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_NotificationMessage extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{
	protected $_dataTypes = array
	( 
		'from' => 'INPUTTEXT', 
		'to' => 'INPUTTEXT', 
		'subject' => 'INPUTTEXT', 
		'body' => 'TEXTAREA', 
		'mode_id' => 'INT, FOREIGN_KEYS = Application_User_NotificationMessage_Mode',
		'creation_date' => 'INT', 'modified_date' => 'INT',
	);
	// END OF CLASS
}
