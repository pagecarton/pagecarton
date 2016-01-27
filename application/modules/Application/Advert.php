<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Advert
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Advert.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Advert
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Advert extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{
	protected $_dataTypes = array
	( 
		'advert_title' => 'INPUTTEXT',
		'advert_content' => 'INPUTTEXT',
		'advert_url' => 'INPUTTEXT',
		'advert_image_url' => 'INPUTTEXT',
		'advert_owner_user_id' => 'INPUTTEXT',
		'advert_creation_date' => 'INT',
		'advert_modified_date' => 'INT',
	);
	// END OF CLASS
}
