<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Dbase_Table_AuthLevel
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: AuthLevel.php 4.11.12 8.48 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml_Protected
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml/Protected.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Dbase_Table_AuthLevel
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_AuthLevel extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.10';

	protected $_dataTypes = array
	( 
		'auth_level' => 'INT,UNIQUE', 
		'auth_name' => 'TEXTAREA,UNIQUE',
		'auth_description' => 'TEXTAREA',
		'display_picture' => 'INPUTTEXT',
		'storage_size' => 'INPUTTEXT',
		'max_allowed_posts' => 'INPUTTEXT',
		'max_allowed_posts_private' => 'INPUTTEXT',
		'parent_access_level' => 'JSON',
		'additional_forms' => 'JSON',
		'auth_options' => 'JSON',
	);
	// END OF CLASS
}
