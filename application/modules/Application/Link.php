<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Link
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Link.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Link
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Link extends Ayoola_Dbase_Table_Abstract_Xml
{
	protected $_dataTypes = array
	( 
		'link_name' => 'INPUTTEXT, UNIQUE',
		'link_url' => 'INPUTTEXT, UNIQUE',
		'link_domain' => 'INPUTTEXT',
		'link_priority' => 'INT',
	);
	// END OF CLASS
}
