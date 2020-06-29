<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Access_LocalUser
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: LocalUser.php 4.11.12 8.48 ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml/Protected.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Access_LocalUser
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Access_LocalUser extends Ayoola_Dbase_Table_Abstract_Xml_Private
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.07';

    /**
     * 
     *
     * @param array
     */
    protected static $_defaultSelectOptions = array( 'supplementary_data_key' => 'user_information' );


	protected $_dataTypes = array 
	( 
		'username' => 'INPUTTEXT,UNIQUE', 
		'email' => 'INPUTTEXT,UNIQUE', 
		'password' => 'INPUTTEXT',
		'access_level' => 'INT',
		'user_information' => 'JSON',   
	);
	// END OF CLASS
}
