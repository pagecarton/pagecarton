<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Abstract_Template_Table
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Template.php date time ayoola $
 */

/**
 * @see Ayoola_Dbase_Table_Abstract_Xml 
 */
 
require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Abstract_Template_Table
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Abstract_Template_Table extends Ayoola_Dbase_Table_Abstract_Xml_Protected
{ 

    /**
     * The Version of the present table (SVN COMPATIBLE)
     *
     * @param int
     */
    protected $_tableVersion = '0.08'; 
   
	protected $_dataTypes = array
	( 
		'template_name' => 'INPUTTEXT,UNIQUE',
		'template_label' => 'INPUTTEXT', 
		'template_screenshot' => 'INPUTTEXT', 
		'markup_template' => 'INPUTTEXT', 
		'markup_template_prefix' => 'INPUTTEXT', 
		'markup_template_suffix' => 'INPUTTEXT', 
		'markup_template_median' => 'INPUTTEXT', 
		'max_group_no' => 'INT', 
		'template_options' => 'JSON', 
		'javascript_files' => 'JSON',  
		'javascript_code' => 'TEXTAREA',  
		'css_code' => 'TEXTAREA',  
		'css_files' => 'JSON', 
	);
	// END OF CLASS
}
