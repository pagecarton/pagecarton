<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Object_Table_Wrapper
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Wrapper.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Object_Table_Wrapper
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Object_Table_Wrapper extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.02'; 

	protected $_dataTypes = array
	( 
		'wrapper_name' => 'INPUTTEXT, UNIQUE ',
		'wrapper_label' => 'INPUTTEXT ',
		'wrapper_prefix' => 'INPUTTEXT',
		'wrapper_suffix' => 'INPUTTEXT',
	);
	// END OF CLASS
}
