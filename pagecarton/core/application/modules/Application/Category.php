<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Category
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Category.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Category
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Category extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.09';

	protected $_dataTypes = array
	( 
		'category_name' => 'INPUTTEXT, UNIQUE',
		'parent_category_name' => 'INPUTTEXT',
		'parent_category' => 'JSON',
		'child_category_name' => 'JSON',
		'category_label' => 'INPUTTEXT',
		'category_url' => 'INPUTTEXT',
		'category_description' => 'INPUTTEXT',
		'category_options' => 'JSON',
		'cover_photo' => 'INPUTTEXT',
	);
	// END OF CLASS
}
