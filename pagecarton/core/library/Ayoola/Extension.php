<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Extension
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Extension.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Extension
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension extends Ayoola_Dbase_Table_Abstract_Xml_Protected // can't be private because child sites inherits the settings here'
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.17'; 

	protected $_dataTypes = array
	( 
		'extension_name' => 'INPUTTEXT, UNIQUE',
		'extension_title' => 'INPUTTEXT',
		'status' => 'INPUTTEXT',
		'settings' => 'JSON',
		'settings_class' => 'INPUTTEXT',
		'components' => 'JSON',
		'modules' => 'JSON',
		'databases' => 'JSON',
		'documents' => 'JSON',
		'plugins' => 'JSON',
		'pages' => 'JSON',
		'templates' => 'JSON',
	);
	// END OF CLASS
}
