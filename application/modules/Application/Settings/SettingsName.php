<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Settings_SettingsName
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: SettingsName.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   Ayoola
 * @package    Application_Settings_SettingsName
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Settings_SettingsName extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.02';

	protected $_dataTypes = array
	( 
		'settingsname_id' => 'INT, RELATIVES = Application_Settings',
		'settingsname_name' => 'INPUTTEXT, UNIQUE',
		'settingsname_editable' => 'INT',
	//	'settings_id' => 'INT, RELATIVES = Application_Settings',
		'document_url' => 'INPUTTEXT',
		'object_name' => 'INPUTTEXT, FOREIGN_KEYS = Ayoola_Object_Table_ViewableObject',
		'class_name' => 'INPUTTEXT',
	);
	// END OF CLASS
}
