<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Table_Module
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Module.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Table_Module
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Object_Table_Module extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{
	protected $_dataTypes = array
	( 
		'module_id' => 'INT, UNIQUE, RELATIVES = Ayoola_Object_Table_ViewableObject',
		'module_name' => 'INPUTTEXT, UNIQUE ',
		'auth_level' => 'INT, FOREIGN_KEYS = Ayoola_Access_AuthLevel'
	);
	// END OF CLASS
}
