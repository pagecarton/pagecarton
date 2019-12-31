<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Page
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Page.php date time ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Page
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Page extends Ayoola_Dbase_Table_Abstract_Xml
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.09';     

	
	protected $_dataTypes = array
	( 
	//	'name' => 'INPUTTEXT,UNIQUE',
		'url' => 'INPUTTEXT,UNIQUE', 
		'redirect_url' => 'INPUTTEXT',    
		'title' => 'INPUTTEXT',
		'description' => 'TEXTAREA', 'keywords' => 'TEXTAREA',
	//	'layout_name' => 'INPUTTEXT, FOREIGN_KEYS = Ayoola_Page_PageLayout', 
		'layout_name' => 'INPUTTEXT',
		'auth_level' => 'ARRAY', 
		'enabled' => 'INT',
		'system' => 'INT',
		'cover_photo' => 'INPUTTEXT',
		'page_options' => 'ARRAY',
		'pagewidget_id' => 'JSON',
		'section_name' => 'JSON',
		'creation_date' => 'INT', 'modified_date' => 'INT',
	);
	// END OF CLASS
}
