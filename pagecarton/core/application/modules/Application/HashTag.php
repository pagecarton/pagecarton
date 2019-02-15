<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   Application
 * @package    Application_HashTag
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: HashTag.php 10-04-2013 12:26 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Application_HashTag
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_HashTag extends Ayoola_Dbase_Table_Abstract_Xml
{	

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.01';

	protected $_dataTypes = array
	( 
		'application_name' => 'INPUTTEXT, UNIQUE',
		'hash_tags' => 'ARRAY',
	);
	// END OF CLASS
}
