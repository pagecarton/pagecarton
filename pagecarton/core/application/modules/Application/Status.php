<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Status
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Status.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Application_Status
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Status extends Ayoola_Dbase_Table_Abstract_Xml_Private
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.05';

	protected $_dataTypes = array
	( 
		'status' => 'INPUTTEXT',
		'object' => 'INPUTTEXT',
		'subject' => 'INPUTTEXT',
		'class_name' => 'INPUTTEXT',
		'timestamp' => 'INT',
		'read_time' => 'INT',
		'featured' => 'INT',
		'auth_level' => 'JSON',
		'reference' => 'JSON',
	);
	// END OF CLASS
}
