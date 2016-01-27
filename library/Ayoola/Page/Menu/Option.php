<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Option
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Option.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml_Protected
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml/Protected.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Menu_Option
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Menu_Option extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.05'; 

	protected $_dataTypes = array
	( 
		'option_name' => 'INPUTTEXT', 'url' => 'INPUTTEXT',
		'menu_id' => 'INPUTTEXT', 'title' => 'INPUTTEXT', 
		'logged_in' => 'INT', 'logged_out' => 'INT', 'append_previous_url' => 'INT', 
		'auth_level' => 'ARRAY', 'enabled' => 'INT', 
		'link_options' => 'ARRAY', 
		'sub_menu_name' => 'INPUTTEXT', 
	);
	// END OF CLASS
}
