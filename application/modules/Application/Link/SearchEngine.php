<?php
/**
 * AyStyle Developer Tool
 *
 * LICENSE
 *
 * @category   Ayoola
 * @package    Application_Link_SearchEngine
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 * @version    $Id: SearchEngine.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   Ayoola
 * @package    Application_Link_SearchEngine
 * @copyright  Copyright (c) 2011-2010 Ayoola Online Inc. (http://www.www.pagecarton.com)
 * @license    http://developer.www.pagecarton.com/aystyle/license/
 */

class Application_Link_SearchEngine extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{
	protected $_dataTypes = array
	( 
		'searchengine_name' => 'INPUTTEXT, UNIQUE',
		'searchengine_url' => 'INPUTTEXT',
		'searchengine_sitemap_url' => 'INPUTTEXT',
	);
	// END OF CLASS
}
