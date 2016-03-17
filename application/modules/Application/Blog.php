<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Blog
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Blog.php 4.17.2012 11.53 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Blog
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Blog extends Ayoola_Dbase_Table_Abstract_Xml
{
	protected $_dataTypes = array
	( 
		'blog_name' => 'INPUTTEXT, UNIQUE',
		'blog_title' => 'INPUTTEXT',
		'blog_description' => 'TEXTAREA',
		'blog_directory' => 'TEXTAREA',
		'blog_tags' => 'INPUTTEXT',
		'blog_creation_date' => 'INT',
		'blog_modified_date' => 'INT',
		'blog_creator_user_id' => 'INT',
		'blog_editor_user_id' => 'INT',
		'enabled' => 'INT',
		'document_id' => 'INT, FOREIGN_KEYS = Ayoola_Doc_Document',
		'category_id' => 'INT, FOREIGN_KEYS = Application_Category',
		'auth_level' => 'INT, FOREIGN_KEYS = Ayoola_Access_AuthLevel',
	);
	// END OF CLASS
}
