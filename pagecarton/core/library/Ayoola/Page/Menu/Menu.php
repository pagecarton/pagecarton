<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Menu
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Menu.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml_Private
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml/Private.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Menu
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Menu_Menu extends Ayoola_Dbase_Table_Abstract_Xml_Private 
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.08'; 

	protected $_dataTypes = array
	( 
		'menu_name' => 'INPUTTEXT,UNIQUE',
		'menu_label' => 'INPUTTEXT',
		'document_url' => 'INPUTTEXT',
		'menu_options' => 'ARRAY', 
		'category_name' => 'JSON', 
		'category_url' => 'INPUTTEXT', 
		'url_integration_type' => 'INPUTTEXT',    
		'sort_order' => 'INPUTTEXT', 
		'enabled' => 'INT', 
	);
	// END OF CLASS
}
