<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Table_ViewableObject
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ViewableObject.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml_Protected
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml/Protected.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Table_ViewableObject
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Object_Table_ViewableObject extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{


    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.04';

	protected $_dataTypes = array
	( 
		'class_name' => 'TEXTAREA', 
		'object_name' => 'TEXTAREA,UNIQUE', 
		'module' => 'JSON', 
		'view_parameters' => 'TEXTAREA',
	);
	// END OF CLASS
}
