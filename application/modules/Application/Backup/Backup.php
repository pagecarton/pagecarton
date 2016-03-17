<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Backup_Backup
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Backup.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml_Private
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Backup_Backup
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Backup_Backup extends Ayoola_Dbase_Table_Abstract_Xml_Private
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.04';

	protected $_dataTypes = array
	( 
		'backup_name' => 'INPUTTEXT, UNIQUE',
		'backup_description' => 'TEXTAREA',
		'backup_filename' => 'INPUTTEXT',
		'backup_options' => 'INPUTTEXT',
		'backup_export_list' => 'ARRAY',
		'export_information' => 'JSON',
		'backup_creation_date' => 'INT',
	);
	// END OF CLASS
}
