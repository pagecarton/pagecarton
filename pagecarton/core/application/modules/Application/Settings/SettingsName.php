<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Settings_SettingsName
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SettingsName.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Settings_SettingsName
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Settings_SettingsName extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.05';

	protected $_dataTypes = array
	( 
		'settingsname_id' => 'INT, RELATIVES = Application_Settings',
		'settingsname_name' => 'INPUTTEXT, UNIQUE',
		'settingsname_title' => 'INPUTTEXT',
	//	'settingsname_editable' => 'INT',
	//	'settings_id' => 'INT, RELATIVES = Application_Settings',
	//	'document_url' => 'INPUTTEXT',
	//	'object_name' => 'INPUTTEXT, FOREIGN_KEYS = Ayoola_Object_Table_ViewableObject',
		'class_name' => 'INPUTTEXT',
	);
	// END OF CLASS
}
