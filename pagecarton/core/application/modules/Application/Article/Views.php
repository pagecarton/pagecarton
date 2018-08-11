<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Article_Views
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Article_Views
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Views extends Ayoola_Dbase_Table_Abstract_Xml_Private
{

    /**
     * The Version of the present table (SVN COMPATIBLE)  
     *
     * @param int
     */
    protected $_tableVersion = '0.03';

    /**
     * Time to hold the cache before refreshing
     *
     * @param int
     */
    public static $cacheTimeOut = 86400;

	protected $_dataTypes = array
	( 
		'username' => 'INPUTTEXT',
		'article_url' => 'INPUTTEXT',
		'timestamp' => 'INPUTTEXT',
	);
	// END OF CLASS
}
