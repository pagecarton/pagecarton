<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Type_Quiz_Table
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Type_Quiz_Table
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Quiz_Table extends Ayoola_Dbase_Table_Abstract_Xml_Private
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.03';

	protected $_dataTypes = array
	( 
		'username' => 'INPUTTEXT',
		'article_url' => 'INPUTTEXT',
		'score' => 'INPUTTEXT',
		'timestamp' => 'INPUTTEXT',
	);
	// END OF CLASS
}
