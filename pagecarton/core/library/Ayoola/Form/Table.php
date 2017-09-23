<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Form_Table
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php date time ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Form_Table
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Form_Table extends Ayoola_Dbase_Table_Abstract_Xml
{

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.11';   

	protected $_dataTypes = array
	( 
		'form_name' => 'INPUTTEXT, UNIQUE, RELATIVES = Ayoola_Form_Table_Data',
		'form_title' => 'INPUTTEXT',
		'form_description' => 'TEXTAREA',
		'form_success_message' => 'TEXTAREA',
		'auth_level' => 'JSON', 
		'email' => 'INPUTTEXT', 
		'group_names' => 'JSON', 
		'group_descriptions' => 'JSON', 
		'group_ids' => 'JSON', 
		'form_options' => 'JSON',
		'requirements' => 'JSON', 
		'callbacks' => 'JSON', 
		'element_title' => 'JSON', 
		'element_placeholder' => 'JSON',
		'element_type' => 'JSON',
		'element_group_name' => 'JSON',
		'element_name' => 'JSON',
		'element_default_value' => 'JSON',
	);
	// END OF CLASS
}
